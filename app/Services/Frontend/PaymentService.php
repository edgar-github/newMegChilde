<?php

namespace App\Services\Frontend;

use App\Events\CouponQuantity;
use App\Http\Controllers\frontend\OrderController;
use App\Models\Order;
use App\Models\Country;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Events\OrderPayment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public ?string $lang = null; // EDP_LANGUAGE for idram

    public function __construct()
    {
        $this->lang = app()->getLocale() === 'hy' ? 'am' : app()->getLocale();
    }

    /**
     * @param Order $order
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|null
     */
    public function makePayment(Order $order): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application|null
    {
        $amount = $order->total_price_with_discount;
        $payment_method = $order->payment_method;
        $order = Order::getOrderWithProducts();

        return match ((int)$payment_method) {
            Order::PAYMENT_METHOD_IDRAM => $this->idramPayment($amount, $order->order_payment_id),
            Order::PAYMENT_METHOD_TELCELL => $this->telcellPayment($amount, $order->order_payment_id),
            Order::PAYMENT_METHOD_BANK => $this->arcaPayment($amount, $order->order_payment_id)
        };
    }


    protected function idramPayment($amount, $order_id)
    {
        $data_idram = [];
        $data_idram['url'] = env('IDRAM_URL');
        $data_idram['EDP_LANGUAGE'] = mb_strtoupper($this->lang);
        $data_idram['EDP_REC_ACCOUNT'] = env('IDRAM_EDP_REC_ACCOUNT');
        $data_idram['EDP_DESCRIPTION'] = 'Վճարում կատարե՛ք idram-ով';
        $data_idram['EDP_AMOUNT'] = $amount;
        $data_idram['EDP_BILL_NO'] = $order_id;

        return view('payments.idram_redirection', compact('data_idram'));
    }

    public function idramCallback(Request $request)
    {
        if ($request->has(['EDP_PRECHECK', 'EDP_BILL_NO', 'EDP_REC_ACCOUNT', 'EDP_AMOUNT']) &&
            $request->EDP_PRECHECK == "YES" &&
            $request->EDP_REC_ACCOUNT == env('IDRAM_EDP_REC_ACCOUNT') &&
            Order::where('order_payment_id', $request->EDP_BILL_NO)->exists()) {

            echo "OK";
        }

        if ($request->has(['EDP_PAYER_ACCOUNT', 'EDP_BILL_NO', 'EDP_REC_ACCOUNT', 'EDP_AMOUNT', 'EDP_TRANS_ID', 'EDP_CHECKSUM'])) {

            $checksum = $this->getIdramChecksum(
                $request->EDP_AMOUNT,
                $request->EDP_BILL_NO,
                $request->EDP_PAYER_ACCOUNT,
                $request->EDP_TRANS_ID,
                $request->EDP_TRANS_DATE);


            $order = Order::getOrderWithProductsByPaymentId($request->EDP_BILL_NO);

            $order->payment_callback = json_encode($request->all());

            if (strtoupper($request->EDP_CHECKSUM) == strtoupper($checksum) &&
                $order->total_price_with_discount == $request->EDP_AMOUNT) {
                $this->send_telegram_message($order);
                event(new OrderPayment(true, $order, Order::STATUS_COMPLETED));

                echo "OK";
            } else {
                event(new OrderPayment(false, $order, Order::STATUS_FAILED));
            }
        }
    }

    protected function getIdramChecksum($endAmount, $endBillNo, $endPayerAccount, $endTransId, $endTransDate)
    {
        $txtToHash =
            env('IDRAM_EDP_REC_ACCOUNT') . ":" .
            $endAmount . ":" .
            env('IDRAM_SECRET_KEY') . ":" .
            $endBillNo . ":" .
            $endPayerAccount . ":" .
            $endTransId . ":" .
            $endTransDate;
        return md5($txtToHash);
    }

    protected function telcellPayment($amount, $order_id)
    {
        $data_telcell = [];
        $data_telcell['url'] = env('TELCELL_URL');
        $data_telcell['issuer'] = env('TELCELL_MERCHANT_ID');
        $data_telcell['action'] = 'PostInvoice'; # always PostInvoice
        $data_telcell['currency'] = "֏"; # always ֏
        $data_telcell['price'] = $amount;
        $data_telcell['product'] = base64_encode('Վճարումն իրականացրե՛ք Telcell Wallet-ով: Խնդրում ենք նկատի ունենալ՝ վճարումն իրականացվելու է հայկական դրամով:');  # description always in base64
        $data_telcell['issuer_id'] = base64_encode($order_id); # order id always in base64
        $data_telcell['valid_days'] = 1; # Число дней, в течении которых счёт действителен.
        $data_telcell['lang'] = $this->lang;
        $data_telcell['security_code'] = $this->getTelcellSecurityCode(
            env('TELCELL_KEY'),
            $data_telcell['issuer'],
            $data_telcell['currency'],
            $data_telcell['price'],

            $data_telcell['product'],
            $data_telcell['issuer_id'],
            $data_telcell['valid_days']
        );

        return view('payments.telcell_redirection', compact('data_telcell'));
    }

    public function telcellCallback(Request $request)
    {
        if (!$request->has(['buyer', 'checksum', 'invoice', 'issuer_id', 'payment_id', 'currency', 'sum', 'time', 'status'])) {
            abort(404);
        }

        $order = Order::getOrderWithProductsByPaymentId($request->issuer_id);

        $checksum = $this->getTelcellChecksum(
            $request->invoice,
            $request->issuer_id,
            $request->payment_id,
            $request->currency,
            $request->sum,
            $request->time,
            $request->status
        );

        if ($request->checksum != $checksum) {
            $order->payment_callback = 'telcell checksum failed';
            event(new OrderPayment(false, $order, Order::STATUS_FAILED));

            abort(404);
        }

        $order->payment_callback = json_encode($request->all());

        if ($request->status == 'PAID') {

            event(new OrderPayment(true, $order, Order::STATUS_COMPLETED));

            if (session()->get('coupon')) {
                event(new CouponQuantity(session()->get('coupon')));
            }
        } else {

            event(new OrderPayment(false, $order, Order::STATUS_FAILED));
        }
    }

    public function telcellRedirect(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (!$request->has('order')) {
            abort(404);
        }

        $order = Order::where('order_payment_id', $request->order)->firstOrFail();

        if ($order->status == Order::STATUS_COMPLETED) {
            $this->send_telegram_message($order);
            return redirect()->route('payment.success');
        } else {

            return redirect()->route('payment.fail');
        }
    }

    protected function getTelcellChecksum($invoice, $issuerId, $paymentId, $currency, $sum, $time, $status)
    {
        return hash('md5',
            env('TELCELL_KEY') .
            $invoice .
            $issuerId .
            $paymentId .
            $currency .
            $sum .
            $time .
            $status
        );
    }

    protected function getTelcellSecurityCode($shop_key, $issuer, $currency, $price, $product, $issuer_id, $valid_days): string
    {
        return hash('md5', $shop_key . $issuer . $currency . $price . $product . $issuer_id . $valid_days);
    }

    public function arcaPayment($amount, $order_id)
    {
        $params = [
            'userName' => env('ARCA_USERNAME'),
            'password' => env('ARCA_PASSWORD'),
            'orderNumber' => $order_id,
            'amount' => $amount * 100,
            'currency' => '051',
            'returnUrl' => route('payment.arca_callback'),
        ];

        $client = new Client();
        $response = $client->request('POST', env('ARCA_URL') . env('ARCA_PAYMENT_EPD'), [
            'form_params' => $params
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        if (!$response['error']) {
            return redirect()->away($response['formUrl']);
        }
        abort(404);
    }

    public function arcaCallback(Request $request)
    {
        if (!$request->has('orderId'))
            abort(404);

        $params = [
            'userName' => env('ARCA_USERNAME'),
            'password' => env('ARCA_PASSWORD'),
            'orderId' => $request->orderId,
        ];

        $client = new Client();
        $response = $client->request('POST', env('ARCA_URL') . env('ARCA_DETAILS_EDP'), [
            'form_params' => $params
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        if ($response['error'])
            return redirect()->route('payment.fail');

        $order = Order::getOrderWithProductsByPaymentId($response['orderNumber']);

        $order->payment_callback = json_encode($response);

        if ($response['orderStatus'] == 2) {

            $this->send_telegram_message($order);
            event(new OrderPayment(true, $order, Order::STATUS_COMPLETED));

            if (session()->get('coupon')) {
                event(new CouponQuantity(session()->get('coupon')));
            }

            return redirect()->route('payment.success');
        } else {
            event(new OrderPayment(false, $order, Order::STATUS_FAILED));

            return redirect()->route('payment.fail');
        }
    }

    public function generateTelegramMessage($data)
    {
        $message = 'Yeraz';
        $message .= "\n\n";
        $message .= 'New order has been placed:';
        $message .= "\n\n";
        $message .= 'Order ID: ' . $data->id;
        $message .= "\n";
        $message .= 'Order date: ' . $data->created_at;
        $message .= "\n";
        $message .= 'Order status: Paid';
        $message .= "\n";
        $message .= 'Order total: ' . $data->total_price . ' ֏';
        $message .= "\n";
        $message .= 'TOTAL PAID:' . $data->total_price_with_discount . ' ֏';
        $message .= "\n";
        $message .= "\n";
        $message .= 'Order payment method: ' . $this->get_payment_method_name($data->payment_method);
        $message .= "\n";
        $message .= 'Shipping address: ' . $data->country->name_en . ' ' . $data->region . ' ' . $data->postal_code . ' ' . $data->street . ' ' . $data->house;
        $message .= "\n";
        $message .= 'Customer: ' . $data->name . ' ' . $data->lastname . ' ' . $data->company;
        $message .= "\n";
        $message .= 'Customer contacts: ' . $data->phone . ' ' . $data->email;
        $message .= "\n\n";
        if ($data->order_text) {
            $message .= 'Comment: ' . $data->order_text;
            $message .= "\n\n";
        }
        $message .= 'Products:';
        $message .= "\n";
        $message .= "\n";
        if(isset($data->books)){
            foreach ($data->books as $book) {
                $quantity = $book->pivot->quantity;
                $message .= $book->title_hy . "\n";
                $message .= 'Price: ' . $book->price . ' ֏ x ' . $quantity . ' = ' . $book->price * $quantity . ' ֏'. "\n";
                $message .= '--------------';
                $message .= "\n";
            }
        }

        return $message;
    }

    public function send_telegram_message($order)
    {
        $botToken = '6800224468:AAHVxUpXCvu8lVaK98ZU9LxZ61QD90Pdcnk';
        $url = "https://api.telegram.org/bot$botToken/sendMessage";

        $chatIds = [
            567142541,
            537760571,
            689205579,
            887371267,
            983775801,
            736628846
        ];

        $message = $this->generateTelegramMessage($order);

        foreach ($chatIds as $chatId) {
            $data = [
                'chat_id' => $chatId,
                'text' => $message,
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_exec($ch);
            curl_close($ch);
        }
    }

    public function get_payment_method_name($id)
    {
        $payment_methods = [
            1 => 'Bank',
            2 => 'Idram',
            3 => 'Telcell',
        ];
        return $payment_methods[$id];
    }

    public function get_status_text($id)
    {
        $statuses = [
            1 => 'New',
            2 => 'Processing',
            3 => 'Completed',
            4 => 'Failed',
            5 => 'Returned',
        ];
        return $statuses[$id];
    }

}
