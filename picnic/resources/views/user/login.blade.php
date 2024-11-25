<?php

use \Illuminate\Support\Facades\Log;
use Illuminate\View\View;

?>
<html lang="fa">
<head>
    <title>ورود کاربران</title>
    <!-- Meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="keywords"
          content="Donuts Login Form Responsive Widget, Audio and Video players, Login Form Web Template, Flat Pricing Tables, Flat Drop-Downs, Sign-Up Web Templates, Flat Web Templates, Login Sign-up Responsive Web Template, Smartphone Compatible Web Template, Free Web Designs for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design"
    />
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <script src="{{URL::to('js/mainPage/jquery.min.js')}}"></script>
    <script src="{{URL::to('assets-switalert/sweetalert2.all.min.js')}}"></script>

    <!-- Meta tags -->
    <link rel="stylesheet" href="{{URL::to('css/mainPage/icomoon.css')}}">
    <!-- font-awesome icons -->
    <link rel="stylesheet" href="{{URL::to('css/login/font-awesome.min.css.css')}}"/>
    <!-- //font-awesome icons -->
    <!--stylesheets-->
    <link href="{{URL::to('css/login/style.css')}}" rel='stylesheet' type='text/css' media="all">
    <!--//style sheet end here-->
    {{--<link href="//fonts.googleapis.com/css?family=Merienda+One" rel="stylesheet">--}}
    {{--<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">--}}
</head>
<body style="direction: rtl">
<h1 class="header-w3ls">
    <div class="col-md-12">
        @if(count($errors) > 0)
            @foreach($errors as $error)
                <ul style="color: darkred">
                    <li style="text-align: center" colspan="2">{{$error}}</li>
                </ul>
            @endforeach
            {{ session()->forget('errors')}}
        @endif

        @if(session('messages'))
            @foreach (session('messages') as $message)
                <ul style="color: green">
                    <li style="text-align: center" colspan="2">{{ $message}}</li>
                </ul>
            @endforeach
            {{ session()->forget('messages')}}
        @endif
    </div>
</h1>
<div class="mid-cls">
    <div class="swm-right-w3ls" >
        <div class="main">
            <div class="icon-head">
                <h2>ورودی کاربران</h2>
            </div>
            <form name="login-form" id="login-form" method="POST" action="{{ url('/users/authenticate') }}">
                {{ csrf_field() }}
                <div class="form-left-w3l">
                    <input id="mobile_login" type="text" name="mobile_login" value="{{ old('mobile_login') }}"
                           placeholder="{{trans('messages.lblMobile')}}" required autofocus>
                    <div class="clear"></div>
                </div>
                <div class="form-right-w3ls ">
                    <input id="password_login" type="password" name="password_login"
                           placeholder="{{trans('messages.lblPassword')}}" required>
                    <div class="clear"></div>
                </div>
                <div class="form-right-w3ls" style="margin: 10px 0px">
                    <div class="col-md-12 form-group div-login-02" style="display: none">
                        <label>
                            <input type="checkbox"
                                   name="remember"> {{trans('messages.lblRememberMe')}}
                        </label>
                    </div>
                    <div class="col-md-12 form-group div-login-02">
                        <div class="refereshrecapcha">
                        {!! captcha_img('math') !!}
                        </div>
                        <br>
                        <button class="btn01 btn btn-default section-btn" onclick="refreshCaptcha(event)">{{trans('messages.btnRefresh')}}</button>
                        <br>
                        <p>{{trans('messages.lblCaptcha')}}:<input type="text" name="captcha" id="captcha"></p>


                        {{--<script src="https://authedmine.com/lib/captcha.min.js" async></script>--}}
                        {{--<div data-callback="myCaptchaCallback" data-disable-elements="button[type=submit]"--}}
                             {{--data-whitelabel="false" class="coinhive-captcha" data-hashes="256"--}}
                             {{--data-key="SlWFDeLelUF8Fyc9i5wVhECIBuzzZWeH">--}}
                            {{--<em>{{trans('messages.msgCaptchaLoading')}}<br>--}}
                                {{--{{trans('messages.msgCaptchaLoadingWait')}}</em>--}}
                        {{--</div>--}}
                        {{--<style>--}}
                            {{--.coinhive-captcha iframe {--}}
                                {{--width: 100% !important;--}}
                            {{--}--}}
                        {{--</style>--}}


                    </div>
                </div>
                <div class="btn">
                    <button id="btnSubmit" type="submit" class="btn01 btn btn-default section-btn">
                        {{trans('messages.lblLogin')}}
                    </button>
                    <br>
                    <a href="{{ url('/users/reset') }}">
                        {{trans('messages.lblForgetPassword')}}
                    </a>
                </div>
            </form>
        </div>
        </form>
    </div>
</div>
<div class="copy">
    <p style="color: black">تمامی حقوق محفوظ است
    </p>
</div>
</body>
<script>

    // $(document).ready(function () {
    //     document.getElementById('btnSubmit').disabled = true;
    //     // var x = document.getElementsByClassName("verify-me-text");
    //     // x[0].textContent  = "";
    //
    //     // var replaced = $("body").html().replace('verified','سلام دوم');
    //     // $("body").html(replaced);
    //     // var replaced = $("body").html().replace('Verify me','سلام اول');
    //     // $("body").html(replaced);
    //
    //
    //
    //     // setTimeout (enlarger, 2000);
    //
    // });
    // var replaced = $("body").html().replace('Verify me','سلام اول');
    // $("body").html(replaced);
    // function enlarger () {
    //     alert('sss');
    //     // document.getElementsByClassName('verify-me-text')[0].innerHTML = "سلام";
    //     // var x = document.getElementsByClassName("verify-me-text");
    //     // x[0].innerHTML = "سلام";
    //     // var elements = document.querySelectorAll('.verify-me-text');
    //     //
    //     // for ( var i=elements.length; i--; ) {
    //     //     alert('dd');
    //     //     elements[i].innerText = "سلام";
    //     // }
    //
    //     var list = document.getElementsByClassName("verify-me-container");
    //     list[0].getElementsByClassName("verify-me-text")[0].innerHTML = "Milk";
    // }
    // function enlarger () {
    //     document.getElementsByClassName('verified-text')[0].innerHTML  = "";
    // }

    // function myCaptchaCallback()
    // {
    //     // alert('sss');
    //     document.getElementById('btnSubmit').disabled = false;
    //     // document.getElementsByClassName('verified-text')[0].innerHTML  = "";
    //     // setTimeout (enlarger, 2000);
    //
    // }

    function refreshCaptcha(e) {

        // alert('ddd');

        // alert('ddd');
        e.preventDefault();
        $.ajax({
            url: '{{url("/refreshCaptcha")}}',
            type: 'get',
            dataType: 'html',
            success: function (json) {
                // alert(json);
                $('.refereshrecapcha').html(json);
            },
            error: function (data) {
                // alert('Try Again.');
                swal({
                    position: 'center',
                    type: 'error',
                })
            }
        });

        return false;
    }

    //captcha
    window.onload = function () {
        setTimeout (enlarger, 2000);
        function enlarger() {
            var count = 0;
            do {
                var xCaptcha = document.getElementsByClassName("verify-me-text");
                count = count + 1;
                if (xCaptcha.length != 0)
                    xCaptcha[0].textContent = 'تشخیص ربات نبودن';
            }
            while (xCaptcha.length == 0 && count < 10000);
        }
    }


</script>
</html>