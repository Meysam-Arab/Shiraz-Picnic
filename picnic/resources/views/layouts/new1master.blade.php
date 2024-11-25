<?php
/**
 * Created by PhpStorm.
 * User: A.sharif
 * Date: 1/28/2019
 * Time: 12:11 PM
 */
?>
<!doctype html>
<html lang="en">

<head>


    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <meta name="google-site-verification" content="WpdwUxwRhZg6GkF7yzwP2MbCq-jk4iWiDYZ21zvhZJs" />

    <title> شیراز پیک نیک - @yield('title') </title>
    <meta name="description" content="@yield('description')">

    {{--<title> شیراز پیک نیک - اجاره باغ های عمومی و خصوصی - تورهای تفریحی و گردشگری</title>--}}
    {{--<meta name="description" content="شیراز پیک نیک محلی برای اجاره باغ های عمومی و خصوصی و رزرو تورهای تفریحی و گردشگری"/>--}}
    <meta name="keywords" content="شیراز پیکنیک - تفریح - تور - باغ - اجاره - گردش - گردشگری - ورزشی - فارس - یوگا - طبیعت"/>
    <meta name="author" content="FREEHTML5.CO"/>
    <meta name="csrf_token" content="{{ csrf_token() }}"/>



    <link rel="icon" href="{{URL::to('images/favicon.png')}}" type="image/png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{URL::to('assets-new1/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets-new1/vendors/linericon/style.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets-new1/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets-new1/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets-new1/vendors/owl-carousel/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets-new1/vendors/lightbox/simpleLightbox.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets-new1/vendors/nice-select/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets-new1/vendors/jquery-ui/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{URL::to('assets-new1/vendors/animate-css/animate.css')}}">
    <!-- main css -->
    <link rel="stylesheet" href="{{URL::to('assets-new1/css/style.css')}}">
    <script src="{{URL::to('assets-new1/js/jquery-3.2.1.min.js')}}"></script>
</head>

<body>

<!--================ Start Header Menu Area =================-->
<div class="menu-trigger">
    <span></span>
    <span></span>
    <span></span>
</div>
<header class="fixed-menu">
    <span class="menu-close"><i class="fa fa-times"></i></span>
    <div class="menu-header">
        <div class="logo d-flex justify-content-center">
            <img src="{{URL::to('images/logo.png')}}" alt="">
        </div>
    </div>
    <div class="nav-wraper">
        <div class="navbar">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="{{url('/')}}"><img src="{{URL::to('images/header/nav-icon1.png')}}" alt="">خانه</a></li>
                <li class="nav-item"><a class="nav-link" href="{{url('/#fh5co-events')}}"><img src="{{URL::to('images/header/nav-icon2.png')}}" alt="">تورهای گردشگری</a></li>
                <li class="nav-item"><a class="nav-link" href="{{url('/#fh5co-featured')}}"><img src="{{URL::to('images/header/nav-icon3.png')}}" alt="">باغ های خصوصی</a></li>
                <li class="nav-item submenu dropdown" style="display: none">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><img src="{{URL::to('images/header/nav-icon6.png')}}" alt="">تخفیف ها</a>
                    <ul class="dropdown-menu">
                        <li class="nav-item"><a class="nav-link" href="elements.html">رستوران</a></li>
                        <li class="nav-item"><a class="nav-link" href="elements.html">کافی شاپ</a></li>
                        <li class="nav-item"><a class="nav-link" href="elements.html">تفریحی</a></li>
                        <li class="nav-item"><a class="nav-link" href="elements.html">ورزشی</a></li>
                        <li class="nav-item"><a class="nav-link" href="elements.html">آموزشی</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{url('/#fh5co-contact')}}"><img src="{{URL::to('images/header/nav-icon8.png')}}" alt="">تماس با ما</a></li>
            </ul>
        </div>
    </div>
</header>
<!--================ End Header Menu Area =================-->

<div class="site-main">
    @yield('content')
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
{{--<script src="{{URL::to('assets-new1/js/jquery-3.2.1.min.js')}}"></script>--}}
<script src="{{URL::to('assets-new1/js/popper.js')}}"></script>
<script src="{{URL::to('assets-new1/js/bootstrap.min.js')}}"></script>
<script src="{{URL::to('assets-new1/js/stellar.js')}}"></script>
<script src="{{URL::to('assets-new1/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{URL::to('assets-new1/vendors/lightbox/simpleLightbox.min.js')}}"></script>
<script src="{{URL::to('assets-new1/vendors/nice-select/js/jquery.nice-select.min.js')}}"></script>
<script src="{{URL::to('assets-new1/vendors/owl-carousel/owl.carousel.min.js')}}"></script>
<script src="{{URL::to('assets-new1/vendors/jquery-ui/jquery-ui.js')}}"></script>
<script src="{{URL::to('assets-new1/js/jquery.ajaxchimp.min.js')}}"></script>
<script src="{{URL::to('assets-new1/vendors/counter-up/jquery.waypoints.min.js')}}"></script>
<script src="{{URL::to('assets-new1/vendors/counter-up/jquery.counterup.js')}}"></script>
<script src="{{URL::to('assets-new1/js/mail-script.js')}}"></script>
<!--gmaps Js-->
{{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>--}}
{{--<script src="{{URL::to('assets-new1/js/gmaps.min.js')}}"></script>--}}
<script src="{{URL::to('assets-new1/js/theme.js')}}"></script>

@yield('page-js-files')
@yield('page-js-script')
</body>

</html>
