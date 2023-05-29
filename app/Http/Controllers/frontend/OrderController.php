<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;

use App\Http\Requests\OrderStoreRequest;
use App\Services\Frontend\OrderService;
use App\Services\Frontend\PaymentService;

class OrderController extends Controller
{
//    protected OrderService $orderService;

    public function __construct(protected OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
//        phpinfo();
        $cardBooks = [];
        if (session()->get('cart')) {
            $regions = $this->orderService->getRegions();
            $countries = $this->orderService->getCountries();
            $cardBooks = $this->orderService->getCartProducts();
            $data = compact('cardBooks', 'regions', 'countries');
        } else {
            $data = compact('cardBooks');
        }

        return view('checkout.checkout', $data);
    }

    public function create(OrderStoreRequest $request)
    {

//        dd($request->all());

        $order = $this->orderService->create($request);


        $this->orderService->createOrderBook($order);

        $payment_service = new PaymentService();

        dd(3);

        return $payment_service->makePayment($order);
    }

}
