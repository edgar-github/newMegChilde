@extends('layout.layout')

@section('content')
    <main class="news-articles">
        <div class="section-tab">
            <div class="wrapper content">
                <div class="tabs_wrap">
                    <ul>
                        <li data-tabs="article" class="article-li">Հոդվածներ</li>
                        <li data-tabs="media" class="media-li">Մեդիա</li>
                    </ul>

                </div>
                <div class="article-tab">
                    <div class="tab-items-article">
                        <div class="item_wrap articles-wrapper open">
                            <div class="articles-list">
                                @foreach($posts as $post)
                                    <div class="articles-list-item">
                                        <div class="articles-list-item-img">
                                            <a href="{{ LaravelLocalization::localizeUrl('/article/' . $post['slug']) }}">
                                                <img src="{{ URL::to('storage/' . $post['image']) }}" alt="">
                                            </a>
                                        </div>
                                        <p>{{ $post['title_' . app()->getLocale()] }}</p>
                                        <span>{{ $post['created_at'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="item_wrap media-wrapper">


                            <div class="media-title">
                                <div class="filter-media-title">
                                    <h1>Մեդիա</h1>
                                </div>
                                <div class="media-filter-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.25 5.22434C7.91848 5.22434 7.60054 5.34137 7.36612 5.54969C7.1317 5.758 7 6.04054 7 6.33515C7 6.62975 7.1317 6.91229 7.36612 7.12061C7.60054 7.32893 7.91848 7.44596 8.25 7.44596C8.58152 7.44596 8.89946 7.32893 9.13388 7.12061C9.3683 6.91229 9.5 6.62975 9.5 6.33515C9.5 6.04054 9.3683 5.758 9.13388 5.54969C8.89946 5.34137 8.58152 5.22434 8.25 5.22434ZM4.7125 5.22434C4.97075 4.57392 5.44967 4.01071 6.08325 3.61232C6.71683 3.21394 7.47386 3 8.25 3C9.02614 3 9.78317 3.21394 10.4167 3.61232C11.0503 4.01071 11.5292 4.57392 11.7875 5.22434H20.75C21.0815 5.22434 21.3995 5.34137 21.6339 5.54969C21.8683 5.758 22 6.04054 22 6.33515C22 6.62975 21.8683 6.91229 21.6339 7.12061C21.3995 7.32893 21.0815 7.44596 20.75 7.44596H11.7875C11.5292 8.09637 11.0503 8.65959 10.4167 9.05797C9.78317 9.45636 9.02614 9.6703 8.25 9.6703C7.47386 9.6703 6.71683 9.45636 6.08325 9.05797C5.44967 8.65959 4.97075 8.09637 4.7125 7.44596H3.25C2.91848 7.44596 2.60054 7.32893 2.36612 7.12061C2.1317 6.91229 2 6.62975 2 6.33515C2 6.04054 2.1317 5.758 2.36612 5.54969C2.60054 5.34137 2.91848 5.22434 3.25 5.22434H4.7125ZM15.75 11.8892C15.4185 11.8892 15.1005 12.0062 14.8661 12.2145C14.6317 12.4229 14.5 12.7054 14.5 13C14.5 13.2946 14.6317 13.5771 14.8661 13.7855C15.1005 13.9938 15.4185 14.1108 15.75 14.1108C16.0815 14.1108 16.3995 13.9938 16.6339 13.7855C16.8683 13.5771 17 13.2946 17 13C17 12.7054 16.8683 12.4229 16.6339 12.2145C16.3995 12.0062 16.0815 11.8892 15.75 11.8892ZM12.2125 11.8892C12.4708 11.2388 12.9497 10.6756 13.5833 10.2772C14.2168 9.87879 14.9739 9.66485 15.75 9.66485C16.5261 9.66485 17.2832 9.87879 17.9167 10.2772C18.5503 10.6756 19.0292 11.2388 19.2875 11.8892H20.75C21.0815 11.8892 21.3995 12.0062 21.6339 12.2145C21.8683 12.4229 22 12.7054 22 13C22 13.2946 21.8683 13.5771 21.6339 13.7855C21.3995 13.9938 21.0815 14.1108 20.75 14.1108H19.2875C19.0292 14.7612 18.5503 15.3244 17.9167 15.7228C17.2832 16.1212 16.5261 16.3351 15.75 16.3351C14.9739 16.3351 14.2168 16.1212 13.5833 15.7228C12.9497 15.3244 12.4708 14.7612 12.2125 14.1108H3.25C2.91848 14.1108 2.60054 13.9938 2.36612 13.7855C2.1317 13.5771 2 13.2946 2 13C2 12.7054 2.1317 12.4229 2.36612 12.2145C2.60054 12.0062 2.91848 11.8892 3.25 11.8892H12.2125ZM8.25 18.554C7.91848 18.554 7.60054 18.6711 7.36612 18.8794C7.1317 19.0877 7 19.3702 7 19.6649C7 19.9595 7.1317 20.242 7.36612 20.4503C7.60054 20.6586 7.91848 20.7757 8.25 20.7757C8.58152 20.7757 8.89946 20.6586 9.13388 20.4503C9.3683 20.242 9.5 19.9595 9.5 19.6649C9.5 19.3702 9.3683 19.0877 9.13388 18.8794C8.89946 18.6711 8.58152 18.554 8.25 18.554ZM4.7125 18.554C4.97075 17.9036 5.44967 17.3404 6.08325 16.942C6.71683 16.5436 7.47386 16.3297 8.25 16.3297C9.02614 16.3297 9.78317 16.5436 10.4167 16.942C11.0503 17.3404 11.5292 17.9036 11.7875 18.554H20.75C21.0815 18.554 21.3995 18.6711 21.6339 18.8794C21.8683 19.0877 22 19.3702 22 19.6649C22 19.9595 21.8683 20.242 21.6339 20.4503C21.3995 20.6586 21.0815 20.7757 20.75 20.7757H11.7875C11.5292 21.4261 11.0503 21.9893 10.4167 22.3877C9.78317 22.7861 9.02614 23 8.25 23C7.47386 23 6.71683 22.7861 6.08325 22.3877C5.44967 21.9893 4.97075 21.4261 4.7125 20.7757H3.25C2.91848 20.7757 2.60054 20.6586 2.36612 20.4503C2.1317 20.242 2 19.9595 2 19.6649C2 19.3702 2.1317 19.0877 2.36612 18.8794C2.60054 18.6711 2.91848 18.554 3.25 18.554H4.7125Z" fill="#8F52A1"/>
                                    </svg>
                                    <h3>Ֆիլտրել</h3>
                                </div>

                            </div>
                            <div class="item">
                                <div class="item_left">
                                    <div class="data section-media-info">
                                        <div class="choose-media-section">
                                            <h3>Ընտրել մեդիան</h3>
                                            <div class="media-scrol-bar scrollbar" id="style-4">
                                                <div class="scroll-bar-all scrol-bar-item">
                                                    <p>Բոլորը</p>
                                                </div>
                                                @foreach($postCategories as $postCategory)
                                                    <div class="scrol-bar-item">
                                                        <div class="scrol-bar-item-img">
                                                            <img src="{{ URL::to($postCategory['image']) }}" alt="">
                                                        </div>
                                                        <a href="{{ LaravelLocalization::localizeUrl('/media/' . $postCategory['id']) }}">
                                                            <p>{{ $postCategory['title_' . app()->getLocale()] }}</p>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="media-list-section">
                                            @foreach($posts as $post)
                                                <div class="media-list-item">
                                                    <div class="media-list-item-img">
                                                        <a href="{{ LaravelLocalization::localizeUrl('/article/' . $post['slug']) }}">
                                                            <img src="{{ URL::to('storage/' . $post['image']) }}" alt="">
                                                        </a>
                                                    </div>
                                                    <p>{{ $post['title_' . app()->getLocale()] }}</p>
                                                    <span>{{ $post['created_at'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
