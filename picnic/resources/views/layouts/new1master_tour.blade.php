<?php
/**
 * Created by PhpStorm.
 * User: A.sharif
 * Date: 1/28/2019
 * Time: 12:11 PM
 */

use \Illuminate\Support\Facades\Log;
use Morilog\Jalali\jDate;
use Illuminate\View\View;

// format the timestamp
$sec = jDate::forge(); // دی 02، 1391
$date = $sec->format('%B %d، %Y');
$dayofWeek = \App\Utilities\Utility::getPersianDayOfWeek($date);
$date = $sec->format($dayofWeek . ' %d %B ، %Y');

?>
        <!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @yield('title')
    @yield('head_start')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{URL::to('images/favicon.png')}}" type="image/png">
    {{--<meta name="description" content="رزرو و ثبت نام در تورهای گردشگری و تفریحی">--}}
    <title>{!! $tour->name !!}</title>
    @if(isset($has_map))
        {{--<link rel="stylesheet" href="https://addmap.parsijoo.ir/leaflet/leaflet.css"/>--}}
        {{--<script src="https://addmap.parsijoo.ir/leaflet/leaflet.js"></script>--}}

        {{--<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"--}}
              {{--integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="--}}
              {{--crossorigin=""/>--}}
        {{--<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"--}}
                {{--integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="--}}
                {{--crossorigin=""></script>--}}
        <link rel="stylesheet" href="{{url('/assets-leaflet/css/leaflet.css')}}"/>
        <script src="{{url('/assets-leaflet/js/leaflet.js')}}"></script>

    @endif
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
    <link href="{{URL::to('css/gallery/fotorama.css')}}" rel="stylesheet">
    <link href="{{URL::to('assets-pDatePicker/persian-datepicker.min.css')}}" rel="stylesheet">
    {{--<script src="{{URL::to('js/dashboard/vendor/jquery-1.10.2.min.js')}}"></script>--}}
    <script src="{{URL::to('assets-new1/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{URL::to('assets-pDatePicker/persian-date.min.js')}}"></script>
    <script src="{{URL::to('assets-pDatePicker/persian-datepicker.min.js')}}"></script>
    <script src="{{URL::to('assets-switalert/sweetalert2.all.min.js')}}"></script>
    @yield('head_end')
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
            <h3 class="profile-title">{!! $tour->name !!}</h3>
            <img src="{{URL::to('images/logo.png')}}" alt="">
        </div>
    </div>
    <div class="nav-wraper">
        <div class="navbar">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#sharh_outBtn"><img
                                src="{{URL::to('images/header/nav-icon2.png')}}" alt="">توضیحات سفر</a></li>
                <li class="nav-item"><a class="nav-link" href="#reserve_form"><img
                                src="{{URL::to('images/header/nav-icon3.png')}}" alt="">رزرو</a></li>
                <li class="nav-item submenu dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button"
                       aria-haspopup="true"
                       aria-expanded="false"><img src="{{URL::to('images/header/nav-icon6.png')}}" alt="">جزئیات تور</a>
                    <ul class="dropdown-menu">
                        <li class="nav-item"><a class="nav-link" href="#ja_outBtn">مبدا و مقصد</a></li>
                        <li class="nav-item"><a class="nav-link" href="#gallery_outBtn">گالری تصاویر</a></li>
                        <li class="nav-item"><a class="nav-link" href="#ser_outBtn">خدمات تور</a></li>
                        <li class="nav-item"><a class="nav-link" href="#extser_outBtn">خدمات بیشتر</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{url('/#footer')}}"><img
                                src="{{URL::to('images/header/nav-icon8.png')}}" alt="">تماس با ما</a></li>
                <li class="nav-item"><a class="nav-link" href="{{url('/')}}"><img
                                src="{{URL::to('images/header/nav-icon1.png')}}" alt="">بازگشت</a></li>
            </ul>
        </div>
    </div>
</header>
<!--================ End Header Menu Area =================-->
<div class="navbarM">
    <div class="navbarM_up">
        <a href="#extser_outBtn"><i class="lnr lnr-user"></i><span>همراه</span></a>
        <a href="#ser_outBtn"><i class="lnr lnr-gift"></i><span>خدمات</span></a>
        <a href="{{URL::to('/')}}" style="padding: 5px 0px 5px 0px !important;border-radius: 0px 0px 10px 10px;"><img
                    style="width: 100%" src="{{URL::to('images/logo.png')}}" alt=""></a>
        <a href="#gallery_outBtn"><i class="lnr lnr-camera"></i><span>گالری</span></a>
        <a href="#sharh_outBtn"><i class="lnr lnr-flag"></i><span>سفر</span></a>
    </div>
    <div class="navbarM_down" style="width: 100%;display: block;float: left;padding: 5px;">
        <div style="width: 40%;float: left;">
            <a href="#reserve_form" class="btn_ds" style="background: #f44a40;width: 35%;">رزرو</a>
            <a href="#contact2" class="btn_ds" style="background: #a863f4;width: 55%;">تکمیل خرید</a>
        </div>
        <div style="width: 60%;float: left;padding: 2px 5px;font-size: 20px;">
            <span>قیمت:</span><span id="final_cost_mobile">نامشخص</span>
        </div>
    </div>
</div>
<style>
    .btn_ds {
        margin-left: 1%;
        margin-right: 1%;
        display: block;
        float: left;
        text-align: center;
        border-radius: 20px;
        color: #fff;
        border: 1px solid transparent;
    }

    .navbarM_down span {
        direction: rtl;
        text-align: right;
        float: right;
    }

    .navbarM_up span {
        font-size: 17px;
    }

    .navbarM_up .lnr {
        display: block;
    }

    .navbarM {
        box-shadow: -6px 0px 20px rgba(0, 0, 0, 0.56);
        border: 0 solid #000;
        z-index: 9999999;
        background-color: #333;
        overflow: hidden;
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    /* Style the links inside the navigation bar */
    .navbarM_up a {
        background-color: white;
        float: left;
        display: block;
        color: #000000;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        width: 20%;
    }

    /* Change the color of links on hover */
    .navbarM a:hover {
        background-color: #ddd;
        color: black;
    }

    /* Add a color to the active/current link */
    .navbarM a.active {
        background-color: #4CAF50;
        color: white;
    }

    @media (max-width: 570px) {
        #footer {
            margin-bottom: 120px;
        }
    }

    .menu-trigger {
        display: none !important;
    }
</style>
<div class="site-main">
    @yield('content')

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
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
{{--<script src="https://maps.googleapis.com/maps/api/js?key=yourkey"></script>--}}
{{--<script src="{{URL::to('assets-new1/js/gmaps.min.js')}}"></script>--}}
<script src="{{URL::to('assets-new1/js/theme.js')}}"></script>

<script src="{{URL::to('js/gallery/fotorama.js')}}"></script>

@yield('page-js-files')
@yield('page-js-script')
</body>

</html>
