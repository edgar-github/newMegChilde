<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="keywords" content="{{ __('layout.og_keywords') }} ">
    <meta name="description" content="{{ __('layout.og_description') }}">
    <meta name="viewport" content="width=device-width">

    <meta property="og:local" content="en_En">
    <meta property="og:type" content="{{ __('layout.article') }}">
    <meta property="og:title" content="Newmage Երազ">
    <meta property="og:description" content="{{ __('layout.og_description') }}">
    <meta property="og:image" content="{{ URL::to('images/newmag-meta-img.png') }}">
    <meta property="og:url" content="">
    <meta property="og:site_name" content="newmage Երազ">
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="Newmage Երազ"/>
    <meta name="twitter:title" content="Newmage Երազ">
    <meta name="twitter:description" content="{{ __('layout.og_description') }}"/>
    <meta name="twitter:image:src" content="{{ URL::to('images/newmag-meta-img.png') }}"/>
    <meta name="twitter:domain" content=""/>

    <title>@yield('title','newmag Երազ')</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::to('images/svg/favicon.svg') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css"
          integrity="sha512-6lLUdeQ5uheMFbWm3CP271l14RsX1xtx+J5x2yeIDkkiBpeVTNhTqijME7GgRKKi6hCqovwCoBTlRBEC20M8Mg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css"
          integrity="sha512-wR4oNhLBHf7smjy0K4oqzdWumd+r5/+6QO/vDda76MW5iug4PT7v86FoEkySIJft3XA0Ae6axhIvHrqwm793Nw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <link rel="stylesheet" href="{{ asset('/css/app.css?v=1.7') }}">
    <link rel="stylesheet" href="{{ asset('/css/media.css?v=1.2') }}">

</head>
<body class="body filter-body">
@include('layout.header')

@yield('content')

@include('layout.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"
        integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('/js/main-jquery.js?v=1.0') }}"></script>
<script src="{{ asset('/js/main.js?v=1.4') }}"></script>
</body>
</html>
