@extends('layout.layout')
@section('title', $book['title_' . app()->getLocale()])
@section('description', $book['description_' . app()->getLocale()])
@section('keywords', $book['keywords_' . app()->getLocale()])
@section('ogimage', URL::to('storage/' . $book['main_image']))
@section('content')
    <main class="news-articles">
        <section class="book-page-item-banner content">
            <div class="book-page-item-banner-img single-item">
                <img src="{{ URL::to('storage/' . $book['main_image']) }}" alt="">
                @foreach($book->images as $img)
                <img src="{{ URL::to('storage/'.$img->image) }}" alt="">
                @endforeach
            </div>
            <div class="book-page-item-banner-info">
                <h2>{{ $book['title_' . app()->getLocale()]  }}</h2>
                <p>{{ __('messages.author') }}`
                    @foreach($book->authors as $key => $author)
                        {{ $author['name_' . app()->getLocale()] }} {{ $key < count($book->authors) - 1 ? ',' : '' }}
                    @endforeach
                </p>
                <div class="book-page-item-param-and-icon">
                    <div class="book-page-item-description-param">
                        <div class="book-page-item-description-param-group">
                            <p>{{ __('messages.group') }}</p>
                            <div class="book-page-item-group-desc-img">
                                <img src="{{ URL::to('/images/' . $book->category->name_en . '.png') }}" alt="">
                            </div>
                        </div>
                        <div class="book-page-item-description-age">
                            <p>{{ __('messages.age') }}</p>
                            <span>{{ $book->category->age }}</span>
                        </div>
                        <div class="book-page-item-description-word-count">
                            <p>{{ __('messages.word_count') }}</p>
                            <span>{{ $book['word_count'] }}</span>
                        </div>
                        <div class="book-page-item-description-font-size">
                            <p>{{ __('messages.font_size') }}</p>
                            <span>{{ $book['font_size'] }}</span>
                        </div>
                    </div>
                    <div class="article-itm-page-banner-desc-icon">
                        <span>{{ __('messages.share') }}:</span>
                        @include('components.social')
                    </div>
                </div>
                <h3>
                    {{ $book['price'] }}֏
                </h3>
                <div class="book-page-item-btn">
{{--                    <a class="add-to-cart" href="{{ LaravelLocalization::localizeUrl('add-to-cart') }}">Գնել</a>--}}
                    @if($book['in_stock'])
                        <a id="add-to-cart" href="#">{{ __('messages.buy') }}</a>
                    @else
                        <div style="color:red; margin: 20px">{{ __('messages.not_available') }}</div>
                    @endif

                    <input id="add-to-cart-url" type="hidden" value="{{ route('addToCart') }}">
                    <input id="checkout-router" type="hidden" value="{{ route('order') }}">
                    <input id="product-id" type="hidden" value="{{ $book['id'] }}">
                    <input id="quantity" type="hidden" value="1" name="number">

                </div>
                <div class="book-item-desc">
                    <h4>{{ __('messages.contents') }}</h4>
                    <p>{{ $book['text_' . app()->getLocale()] }}</p>
                    <div class="l_more">
                        <button class="learn-more-btn">{{ __('messages.read_more') }}</button>
                    </div>
                </div>
            </div>
        </section>
        <section class="book-page-item-full-information content  accordion-container">
            <div class="book-page-item-additional-information  accordion">
                <h2 class="accordion-title">{{ __('messages.additional_information') }}</h2>
                <div class="book-page-item-additional-information-list  accordion-text" >
                    <div class="additional-information-list-item">
                        <p>{{ __('messages.original_name') }}</p>
                        <div class="additional-information-list-item-img">
                            <img src="{{ URL::to('/images/Line2.png') }}" alt="line">
                        </div>
                        <span >{{ $book['title_' . app()->getLocale()]  }}</span>
                    </div>
                    <div class="additional-information-list-item">
                        <p></p>
                        <div class="additional-information-list-item-img">
                            <img src="{{ URL::to('/images/Line2.png') }}" alt="line">
                        </div>
                        <span>{{ $book->category->age }} {{ __('messages.years') }}</span>
                    </div>
                    <div class="additional-information-list-item">
                        <p>ISBN</p>
                        <div class="additional-information-list-item-img">
                            <img src="{{ URL::to('/images/Line2.png') }}" alt="line">
                        </div>
                        <span>{{ $book['isbn']  }}</span>
                    </div>
                    <div class="additional-information-list-item">
                        <p>{{ __('messages.published_date') }}</p>
                        <div class="additional-information-list-item-img">
                            <img src="{{ URL::to('/images/Line2.png') }}" alt="line">
                        </div>
                        <span>{{ $book['published_date']  }}</span>
                    </div>
                    <div class="additional-information-list-item">
                        <p>{{ __('messages.page_count') }}</p>
                        <div class="additional-information-list-item-img">
                            <img src="{{ URL::to('/images/Line2.png') }}" alt="line">
                        </div>
                        <span>{{ $book['page_count']  }}</span>
                    </div>
                    <div class="additional-information-list-item">
                        <p>{{ __('messages.size') }}</p>
                        <div class="additional-information-list-item-img">
                            <img src="{{ URL::to('/images/Line2.png') }}" alt="line">
                        </div>
                        <span>{{ $book['book_size_' . app()->getLocale()]  }}</span>
                    </div>
                </div>

                <button class="accordion-toggle">
                    <img src="{{ URL::to('/images/svg/arrow-down-circle.svg') }}" alt="arrow down">
                </button>
            </div>

            <svg width="1" class="svg-additional-information" height="516" viewBox="0 0 1 516" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <line x1="0.5" x2="0.5" y2="516" stroke="#F36F21"/>
            </svg>

            <div class="book-page-item-after  accordion">
                <h2  class="faq-title">{{ __('messages.authors') }}</h2>
                @foreach($book->authors as $key => $author)
                    <div class="accordion-text">
                        <div class="book-page-item-after-img">
                            <img src="{{ URL::to('storage/' . $author['image']) }}" alt="">
                        </div>
                        <p>{{ $author['about_' . app()->getLocale()] }}</p>
                    </div>
                @endforeach
                <button class="accordion-toggle">
                    <img src="{{ URL::to('/images/svg/arrow-down-circle.svg') }}" alt="arrow down">
                </button>
            </div>

            <svg width="1" class="svg-additional-information" height="516" viewBox="0 0 1 516" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <line x1="0.5" x2="0.5" y2="516" stroke="#F36F21"/>
            </svg>

            <div class="book-page-item-book-trailer  accordion">
                <h2 class="accordion-title">{{ __('messages.book_trailer') }}</h2>
                <div class="book-page-item-book-trailer-video  accordion-text">
                    <iframe src="https://www.youtube.com/embed/cGmLS5KL7yw" title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                </div>
                <button class="accordion-toggle">
                    <img src="{{ URL::to('/images/svg/arrow-down-circle.svg') }}" alt="arrow down">
                </button>
            </div>
        </section>
    </main>
@endsection

