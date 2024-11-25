<?php

use \Illuminate\Support\Facades\Log;
use Morilog\Jalali\jDate;
use Illuminate\View\View;

// format the timestamp
$sec = jDate::forge(); // دی 02، 1391
$date = $sec->format('%B %d، %Y');
$dayofWeek = \App\Utilities\Utility::getPersianDayOfWeek($date);
$date = $sec->format($dayofWeek . ' %d %B ، %Y');

?>
        <!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
@yield('title')
@yield('head_start')
{{--    <title>{!! $tour->name !!}</title>--}}
    {{--<meta name="description" content="رزرو و ثبت نام در تورهای گردشگری و تفریحی">--}}
    <!--
    Volton Template
    http://www.templatemo.com/tm-441-volton
    -->
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
{{--        <link rel="stylesheet" href="{{url('/assets-leaflet/css/leaflet-routing-machine.css')}}" />--}}
        <script src="{{url('/assets-leaflet/js/leaflet.js')}}"></script>
{{--        <script src="{{url('/assets-leaflet/js/leaflet-routing-machine.js')}}"></script>--}}

    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{URL::to('css/dashboard/normalize.css')}}">
    <link rel="stylesheet" href="{{URL::to('css/dashboard/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{URL::to('css/dashboard/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{URL::to('css/dashboard/templatemo-style.css')}}">
    <link href="{{URL::to('css/gallery/fotorama.css')}}" rel="stylesheet">
    <link href="{{URL::to('assets-pDatePicker/persian-datepicker.min.css')}}" rel="stylesheet">


    <script src="{{URL::to('js/dashboard/vendor/modernizr-2.6.2.min.js')}}"></script>
    <script src="{{URL::to('js/dashboard/vendor/jquery-1.10.2.min.js')}}"></script>
{{--    <script src="{{URL::to('js/dashboard/min/bootstrap.min.js')}}"></script>--}}
    {{--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>--}}
    <script src="{{URL::to('assets-pDatePicker/persian-date.min.js')}}"></script>
    <script src="{{URL::to('assets-pDatePicker/persian-datepicker.min.js')}}"></script>
    <script src="{{URL::to('assets-switalert/sweetalert2.all.min.js')}}"></script>
    @yield('head_end')
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<div class="responsive-header visible-xs visible-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="top-section">
                    <div class="profile-image profile-image-tour-avatar">
                        <img src="{{URL::to('users/getFile/'.$owner->user_id.'/'.$owner->user_guid.'/1.11')}}" alt="">
                    </div>
                    <div class="profile-content">
                        <h3 class="profile-title">{!! $tour->name !!}</h3>
                        {{--<p class="profile-description">{!! \App\Tour::getTypeStringByCode($tour->type) !!}</p>--}}
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="toggle-menu"><i class="fa fa-bars"></i></a>
        <div class="main-navigation responsive-menu">
            <ul class="navigation">
                <li><a href="#address"><i class="fa fa-home"></i>آدرس</a></li>
                <li><a href="#about"><i class="fa fa-image"></i>گالری تصاویر</a></li>
                <li><a href="#Conditions"><i class="fa fa-briefcase"></i>  شرایط و قوانین</a></li>
                <li><a href="#projects"><i class="fa fa-briefcase"></i> خدمات تور</a></li>
                <li><a href="#contact2"><i class="fa fa-pencil"></i>رزرو</a></li>
                <li><a href="{{url('/')}}"><i class="fa fa-backward"></i>صفحه اصلی</a></li>
                {{--<li>--}}
                {{--<button href="{{url('/')}}"  class="btn btn-primary"--}}
                        {{--style="max-width:30%;display: block;margin-left: auto;margin-right: auto"> صفحه اصلی </button>--}}
                {{--</li>--}}
            </ul>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<div class="sidebar-menu hidden-xs hidden-sm">
    <div class="top-section">
        <div class="profile-image profile-image-tour-avatar">
            <img src="{{URL::to('users/getFile/'.$owner->user_id.'/'.$owner->user_guid.'/1.11')}}" alt="مسئول تور">
        </div>
        {{--<h3 class="profile-title">{!! $tour->name !!} </h3>--}}
        <h3 class="profile-title">
            @if($tour->cost == 0)
                رایگان
            @else
                {{number_format($tour->cost)}} تومان
            @endif

        </h3>
        {{--<p class="profile-description">{!! \App\Tour::getTypeStringByCode($tour->type) !!}</p>--}}
    </div> <!-- top-section -->
    <div class="main-navigation">
        <ul class="navigation">
            <li><a href="#address"><i class="fa fa-home"></i>آدرس</a></li>
            <li><a href="#about"><i class="fa fa-image"></i>گالری تصاویر</a></li>
            <li><a href="#Conditions"><i class="fa fa-list"></i>  شرایط و قوانین</a></li>
            <li><a href="#projects"><i class="fa fa-briefcase"></i> خدمات تور</a></li>
            <li><a href="#contact2"><i class="fa fa-pencil"></i>رزرو</a></li>
        </ul>
    </div> <!-- .main-navigation -->
    @if(isset($socials))
        <div class="social-icons">
            <ul>
                @foreach($socials as $social)
                    @if($social->code == \App\Tour::SocialInstagram)
                        <li><a target="_blank" href="{!! $social->address !!}"><i class="fa fa-instagram" title="اینستاگرام"></i></a></li>
                    @elseif($social->code == \App\Tour::SocialFacebook)
                        <li><a target="_blank" href="{!! $social->address !!}"><i class="fa fa-facebook" title="فیس بوک">></i></a></li>
                    @elseif($social->code == \App\Tour::SocialTelegram)
                        <li><a target="_blank" href="{!! $social->address !!}"><i class="fa fa-telegram" title="تلگرام"></i></a></li>
                    @elseif($social->code == \App\Tour::SocialTwitter)
                        <li><a target="_blank" href="{!! $social->address !!}"><i class="fa fa-twitter" title="تلگرام"></i></a></li>
                    @else
                        <li><a target="_blank" href="{!! $social->address !!}"><i class="fa fa-user" title=""></i></a></li>
                    @endif
                @endforeach
                {{--<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
                {{--<li><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
                {{--<li><a href="#"><i class="fa fa-linkedin"></i></a></li>--}}
                {{--<li><a href="#"><i class="fa fa-google-plus"></i></a></li>--}}
                {{--<li><a href="#"><i class="fa fa-youtube"></i></a></li>--}}
                {{--<li><a href="#"><i class="fa fa-rss"></i></a></li>--}}
            </ul>
        </div> <!-- .social-icons -->
    @endif
    <div>
        <a href="{{url('/')}}" class="btn btn-primary"
           style="max-width:30%;display: block;margin-left: auto;margin-right: auto"> صفحه اصلی </a>
        {{--<button href="{{url('/')}}"  class="btn btn-primary"--}}
           {{--style="max-width:30%;display: block;margin-left: auto;margin-right: auto"> صفحه اصلی </button>--}}

    </div>
</div> <!-- .sidebar-menu -->

@yield('content')

<script src="{{URL::to('js/dashboard/min/plugins.min.js')}}"></script>
<script src="{{URL::to('js/dashboard/min/main.min.js')}}"></script>
<script src="{{URL::to('js/gallery/fotorama.js')}}"></script>
@yield('page-js-files')
@yield('page-js-script')
</body>
</html>
