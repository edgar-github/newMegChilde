<header>
    <div class="header content">
        <div id="nav-icon1">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="header-logo">
            <a href="{{ LaravelLocalization::localizeUrl('/') }}">
                <img src="{{ URL::to('/images/svg/logo.svg') }}" alt="logo images">
            </a>
        </div>

        <div class="header-menu">
            <nav class="nav">
                <ul class="header-menu-list">
                    <li class="menu-about-us {{ isRouteActive('about') ? 'active-menu-underline' : '' }}">Մեր մասին
                        <ul class="menu-drop-down">
                            <li><a href="{{ LaravelLocalization::localizeUrl('/about') }}" class="{{ isRouteActive('about') ? 'active-menu-underline' : '' }}">Մատենաշարի մասին</a></li>
                            <li><a href="{{ LaravelLocalization::localizeUrl('/contact-us') }}">Կապ</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ LaravelLocalization::localizeUrl('/books') }}" class="{{ isRouteActive('books') ? 'active-menu-underline' : '' }}">Գրքեր...</a></li>
                    <li><a href="{{ LaravelLocalization::localizeUrl('/authors') }}" class="{{ isRouteActive('authors') ? 'active-menu-underline' : '' }}">Հեղինակներ</a></li>
                    <li><a href="{{ LaravelLocalization::localizeUrl('/translators') }}" class="{{ isRouteActive('translators') ? 'active-menu-underline' : '' }}">Թարգմանիչներ</a></li>
                    <li  class="menu-drop-down-articles">
                         Հոդվածներ
                        <ul class="drop-down-articles">
                            <li><a href="{{ LaravelLocalization::localizeUrl('/articles') }}" class="{{ isRouteActive('articles') ? 'active-menu-underline' : '' }}">Լուրեր</a></li>
                            <li><a href="{{ LaravelLocalization::localizeUrl('/media-articles') }}">Մեդիա</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="header-icon">
            <div class="header-icon-serch search">
                <img src="{{ URL::to('/images/svg/search-logo.svg') }}" alt="search logo">
            </div>
            <div class="header-icon-img">
                <img src="{{ URL::to('/images/Line%202.png') }}" alt="line">
            </div>

            <div class="header-icon-shop">
                @if(session()->get('cart')) <sub>{{ count(session()->get('cart')) }}</sub> @endif
                    <a href="{{ route('order') }}">
                        <img src="{{ URL::to('/images/svg/shopping-cart.svg') }}" alt="sopping cart logo">
                    </a>
            </div>
            <div class="header-icon-img">
                <img src="{{ URL::to('/images/Line%202.png') }}" alt="line">
            </div>
            <div class="header-icon-leng">
                <p>EN</p>
            </div>



        </div>
        @include('components.search')
    </div>

</header>
@include('components.loader')
