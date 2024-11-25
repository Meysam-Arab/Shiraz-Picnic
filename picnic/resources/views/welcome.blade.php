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
    <meta name="google-site-verification" content="WpdwUxwRhZg6GkF7yzwP2MbCq-jk4iWiDYZ21zvhZJs" />
    <title> شیراز پیک نیک - اجاره باغ های عمومی و خصوصی - تورهای تفریحی و گردشگری</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="شیراز پیک نیک محلی برای اجاره باغ های عمومی و خصوصی و رزرو تورهای تفریحی و گردشگری"/>
    <meta name="keywords" content="شیراز پیکنیک - تفریح - تور - باغ - اجاره - گردش"/>
    <meta name="author" content="FREEHTML5.CO"/>
    <meta name="csrf_token" content="{{ csrf_token() }}"/>

    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content=""/>
    <meta property="og:image" content=""/>
    <meta property="og:url" content=""/>
    <meta property="og:site_name" content=""/>
    <meta property="og:description" content=""/>
    <meta name="twitter:title" content=""/>
    <meta name="twitter:image" content=""/>
    <meta name="twitter:url" content=""/>
    <meta name="twitter:card" content=""/>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="favicon.ico">

    <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700,400italic,700italic|Merriweather:300,400italic,300italic,400,700italic'
          rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="{{URL::to('css/mainPage/jquery-ui.min.css')}}"/>

    <!-- Animate.css -->
    <link rel="stylesheet" href="{{URL::to('css/mainPage/animate.css')}}">
    <!-- Icomoon Icon Fonts-->
    <link rel="stylesheet" href="{{URL::to('css/mainPage/icomoon.css')}}">
    <!-- Simple Line Icons -->
    <link rel="stylesheet" href="{{URL::to('css/mainPage/simple-line-icons.css')}}">
    <!-- Datetimepicker -->
    <link rel="stylesheet" href="{{URL::to('css/mainPage/bootstrap-datetimepicker.min.css')}}">
    <!-- Flexslider -->
    <link rel="stylesheet" href="{{URL::to('css/mainPage/flexslider.css')}}">
    <!-- Bootstrap  -->
    <link rel="stylesheet" href="{{URL::to('css/mainPage/bootstrap.css')}}">

    <link rel="stylesheet" href="{{URL::to('css/mainPage/style.css')}}">


    <!-- Modernizr JS -->
    <script src="{{URL::to('js/mainPage/modernizr-2.6.2.min.js')}}"></script>
    <!-- FOR IE9 below -->
    <!--[if lt IE 9]>
    <script src="{{URL::to('js/mainPage/respond.min.js')}}"></script>
    <![endif]-->

</head>
<body>


<div class="table-header">
    @if(count($errors) > 0)
        <div class="alert alert-error">
            @foreach($errors as $error)
                <ul style="color: darkred">
                    <li style="text-align: center" colspan="2">{{$error}}</li>
                </ul>
            @endforeach
        </div>
        {{ session()->forget('errors')}}
    @endif

    @if(session('messages'))
        <div class="alert alert-warning">
            @foreach (session('messages') as $message)
                <ul style="color: blue">
                    <li style="text-align: center" colspan="2">{{ $message}}</li>
                </ul>
            @endforeach
        </div>
        {{ session()->forget('messages')}}
    @endif
</div>

<div id="fh5co-container">
    <div id="fh5co-home" class="js-fullheight" data-section="home">

        <div class="flexslider">

            <div class="fh5co-overlay"></div>
            <div class="fh5co-text">
                <div class="container">
                    <div class="row">
                        {{--<h1 class="to-animate">foodee</h1>--}}
                        <img src="{{URL::to('images/logo_big_a.png')}}" class="img-responsive" alt="شیراز-پیک نیک"
                             style="margin-top: 150px;">
                        <h2 class="to-animate">برگزاری تورهای گردشگری و اجاره باغ های خصوصی</h2>
                    </div>
                </div>
            </div>
            <ul class="slides">
                <li style="background-image: url({{URL::to('tours/getFile/2/5b6bdb3db686f7.69335791/5/3.22')}});"
                    data-stellar-background-ratio="0.4"></li>
                <li style="background-image: url({{URL::to('images/mainPage/slide_2.jpg')}});"
                    data-stellar-background-ratio="0.4"></li>
                <li style="background-image: url({{URL::to('images/mainPage/slide_3.jpg')}});"
                    data-stellar-background-ratio="0.4"></li>
                <li style="background-image: url({{URL::to('tours/getFile/3/5b6bdb3db686f7.69335792/1/3.22')}});"
                    data-stellar-background-ratio="0.4"></li>
            </ul>

        </div>

    </div>
    <div class="js-sticky">
        <div class="fh5co-main-nav">
            <div class="container-fluid">
                <div class="fh5co-menu-1">
                    <a href="#" data-nav-section="home">خانه</a>
                    {{--<a href="#" data-nav-section="about">About</a>--}}
                    <a href="#" data-nav-section="features">رزرو باغ</a>
                </div>
                <div class="fh5co-logo">
                    <a href="{{url('/')}}" style="padding: 15px 0px !important;"><img
                                src="{{URL::to('images/logo_big_a.png')}}" class="img-responsive" alt="شیراز-پیک نیک"></a>
                </div>
                <div class="fh5co-menu-2">
                    {{--<a href="#" data-nav-section="menu">Menu</a>--}}
                    <a href="#" data-nav-section="events">تورهای تفریحی</a>
                    <a href="#" data-nav-section="reservation">تماس با ما</a>
                </div>
            </div>

        </div>
    </div>
    <div id="fh5co-events" data-section="events"
         style="background-image: url({{URL::to('images/mainPage/slide_1.jpg')}});direction: rtl"
         data-stellar-background-ratio="0.5">
        <div class="fh5co-overlay"></div>
        <div class="container">
            <div class="row text-center fh5co-heading row-padded">
                <div class="col-md-8 col-md-offset-2 to-animate">
                    <h2 class="heading">تورها</h2>
                    <p class="sub-heading">در این قسمت لیست تورها را مشاهده می کنید</p>
                </div>
            </div>
            <div class="row">


                @if(isset($tours))
                    @foreach($tours as $tour)
                        <?php
                        $is_ended = false;
                        $temp_start_date = \Carbon\Carbon::parse($tour->miladi_start_date_time);
                        $temp_start_date = \Carbon\Carbon::parse($temp_start_date->toDateString());
                        if($temp_start_date->lt(\Carbon\Carbon::now()))
                            $is_ended = true;

                        $temp_end_date = \Carbon\Carbon::parse($tour->miladi_end_date_time);
                        $temp_end_date = \Carbon\Carbon::parse($temp_end_date->toDateString());
                        $is_in_one_day = false;
                        if($temp_start_date->eq($temp_end_date))
                            $is_in_one_day = true;

                        $is_ended_capacity = false;
                        if($tour->remaining_capacity <= 0)
                            $is_ended_capacity = true;
                        ?>
                        <div class="col-md-4" style="float: right">
                            <div class="fh5co-event to-animate-2">
                                <h3>{{$tour->title}}</h3>
                                <img src="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/1/3.33')}}" class="img-responsive2" style="min-height: 200px;max-height: 200px;"
                                     alt="شیراز-پیک نیک">
                                <div class="container" style="position: relative;">
                                    <img src="{{URL::to('users/getFile/'.$tour->owner->user_id.'/'.$tour->owner->user_guid.'/1.11')}}" class="header-profile circle">
                                </div>
                                <span class="fh5co-event-meta" style="margin-top: 80px">{{$tour->tour_address->address}}</span>

                                @if($is_ended)
                                    <p style="direction: rtl">برگزار شد</p>
                                    @if($is_in_one_day)
                                        <p >  {{$tour->start_date_time}} <br><br> </p>
                                    @else
                                        <p >  {{$tour->start_date_time}} از <br>{{$tour->end_date_time}} تا </p>
                                    @endif

                                    <p><a href="{{url("/tours/detail/{$tour->tour_id}")}}" class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>

                                @else
                                    @if($is_ended_capacity)
                                        <p style="direction: rtl">ظرفیت تکمیل شد</p>
                                        @if($is_in_one_day)
                                            <p >  {{$tour->start_date_time}} <br><br> </p>
                                        @else
                                            <p >  {{$tour->start_date_time}} از <br>{{$tour->end_date_time}} تا </p>
                                        @endif

                                        <p><a href="{{url("/tours/detail/{$tour->tour_id}")}}" class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>

                                    @else
                                        @if($tour->cost == 0)
                                            <p style="direction: rtl"> رایگان</p>
                                        @else
                                            @if($tour->stroked_cost != null && $tour->stroked_cost > 0)
                                                <del>{{number_format($tour->stroked_cost)}} </del>  &nbsp;  <p style="direction: rtl"> {{number_format($tour->cost)}} {{trans('messages.lblToman')}}</p>
                                            @else
                                                <p style="direction: rtl"> {{number_format($tour->cost)}} {{trans('messages.lblToman')}}</p>
                                            @endif

                                        @endif                                    @if($is_in_one_day)
                                            <p >  {{$tour->start_date_time}} <br> <br></p>
                                        @else
                                            <p >  {{$tour->start_date_time}} از <br>{{$tour->end_date_time}} تا </p>
                                        @endif
                                        <p><a href="{{url("/tours/detail/{$tour->tour_id}")}}" class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>

                                    @endif


                                @endif


                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <a href="{{url("/tours/listAll")}}" class="btn btn-primary btn-down-01">نمایش تمامی تور ها</a>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>
    <div id="fh5co-featured" data-section="features" style="">
        <div class="container">
            <div class="row text-center fh5co-heading row-padded">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="heading to-animate">لیست باغ ها</h2>
                    <p class="sub-heading to-animate">با کلیک بر روی هر باغ می توانید جزئیات آن را مشاهده کنید</p>
                </div>
            </div>
            <div class="row">
                <div class="fh5co-grid">
                    <div class="fh5co-v-half to-animate-2">
                        <div class="fh5co-v-col-2 fh5co-bg-img"
                             style="background-image: url({{URL::to('gardens/getFile/1/5b6bdb3db486f7.69335790/2/2.22')}})"></div>
                        <div class="fh5co-v-col-2 fh5co-text fh5co-special-1 arrow-left" style="direction: rtl">
                            <h2>باغ چهار فصل</h2>
                            <span class="pricing">...</span>
                            <p>45 کیلومتری شیراز</p>
                            <div class="form-group ">
                                {{--<a href="{{url("/gardens/detail/{$garden->garden_id}")}}" class="btn btn-primary">نمایش جزییات و رزرو</a>--}}
                                <a class="btn btn-primary" disabled="true">به زودی</a>

                                {{--<input class="btn btn-primary" value="ارسال پیام" type="submit">--}}
                            </div>
                        </div>
                    </div>
                    <div class="fh5co-v-half">
                        <div class="fh5co-h-row-2 to-animate-2">
                            <div class="fh5co-v-col-2 fh5co-bg-img"
                                 style="background-image: url({{URL::to('images/mainPage/slide_3.jpg')}})"></div>
                            <div class="fh5co-v-col-2 fh5co-text arrow-left" style="direction: rtl">
                                <h2>محل تبلیغ باغ شما</h2>
                                <span class="pricing">...</span>
                                <p>...</p>
                            </div>
                        </div>
                        <div class="fh5co-h-row-2 fh5co-reversed to-animate-2">
                            <div class="fh5co-v-col-2 fh5co-bg-img"
                                 style="background-image: url({{URL::to('images/mainPage/slide_1.jpg')}})"></div>
                            <div class="fh5co-v-col-2 fh5co-text arrow-right" style="direction: rtl">
                                <h2>محل تبلیغ باغ شما</h2>
                                <span class="pricing">...</span>
                                <p>...</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <a class="btn btn-primary btn-down-01" disabled>نمایش تمامی باغ ها</a>
                    {{--<a href="{{url("/gardens/listAll")}}" class="btn btn-primary btn-down-01">نمایش تمامی باغ ها</a>--}}

                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>
    <div id="fh5co-contact" data-section="reservation" style="direction: rtl">
        <div class="container">
            <div class="row text-center fh5co-heading row-padded">
                <div class="col-md-8 col-md-offset-2">
                    <h2 class="heading to-animate">تماس با ما</h2>
                    <p class="sub-heading to-animate">در صورت تمایل جهت قرار دادن تور یا باغ خود در سایت با ما تماس بگیرید</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 to-animate-2">
                    <h3>فرم تماس</h3>
                    <div class="form-group ">
                        <label for="name_and_family" class="sr-only">نام و نام خانوادگی شما</label>
                        <input id="name_and_family" name="name_and_family" class="form-control"
                               placeholder="نام و نام خانوادگی شما" type="text">
                    </div>
                    <div class="form-group ">
                        <label for="tel" class="sr-only">{{trans('messages.lblTel')}}</label>
                        <input id="tel" name="tel" class="form-control" placeholder="{{trans('messages.lblTel')}}"
                               type="email">
                    </div>
                    <div class="form-group ">
                        <label for="email" class="sr-only">{{trans('messages.lblEmail')}}</label>
                        <input id="email" class="form-control" placeholder="{{trans('messages.lblEmail')}}"
                               type="email">
                    </div>
                    <div class="form-group">
                        <label for="occasion" class="sr-only">Occation</label>
                        <select class="form-control" id="occasion" name="occasion">
                            <option>نوع خدمت ما</option>
                            <option>می خواهم باغم را اجاره دهم</option>
                            <option>سایر</option>
                        </select>
                    </div>
                    {{--<div class="form-group ">--}}
                    {{--<label for="date" class="sr-only">Date</label>--}}
                    {{--<input id="date" class="form-control" placeholder="Date &amp; Time" type="text">--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label for="description" class="sr-only">پیام</label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                  placeholder="پیام (اجباری)"></textarea>
                    </div>

                    <div class="form-group">
                        <div class="form-group refereshrecapcha">
                        {!! captcha_img('flat') !!}
                        </div>
                        <button class="btn" onclick="refreshCaptcha()">{{trans('messages.btnRefresh')}}</button>
                        <br>
                        <p>{{trans('messages.lblCaptcha')}}:<input type="text" name="captcha" id="captcha"></p>

                        {{--<script src="https://authedmine.com/lib/captcha.min.js" async></script>--}}
                        {{--<div id="sd"--}}
                             {{--data-disable-elements="button[type=submit]"--}}
                             {{--data-whitelabel="false" class="coinhive-captcha" data-hashes="1024"--}}
                             {{--data-key="SlWFDeLelUF8Fyc9i5wVhECIBuzzZWeH">--}}
                            {{--<em>{{trans('messages.msgCaptchaLoading')}}<br>--}}
                                {{--{{trans('messages.msgCaptchaLoadingWait')}}</em>--}}
                        {{--</div>--}}
                    </div>


                    <div class="form-group ">
                        <input onclick="saveFeedbackFunction()" class="btn btn-primary" value="ارسال پیام"
                               type="submit">
                    </div>
                </div>
                <div class="col-md-6 to-animate-2">
                    <h3>اطلاعات تماس</h3>
                    <ul class="fh5co-contact-info">
                        <li class="fh5co-contact-address ">
                            <i class="icon-home"></i>
                            آدرس: شیراز - خیابان طالقانی
                        </li>
                        <li><i class="icon-phone"></i> 09029027302</li>
                        <li><i class="icon-envelope"></i>info@shirazpicnic.ir</li>
                        {{--<li><i class="icon-globe"></i> <a href="http://freehtml5.co/" target="_blank">freehtml5.co</a>--}}
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="fh5co-footer">
    <div class="container">
        <div class="row row-padded">
            <div class="col-md-12 text-center">
                {{--<p class="to-animate">&copy; 2016 Foodee Free HTML5 Template. <br> Designed by <a--}}
                {{--href="http://freehtml5.co/" target="_blank">FREEHTML5.co</a> Demo Images: <a--}}
                {{--href="http://pexels.com/" target="_blank">Pexels</a> <br> Tasty Icons Free <a--}}
                {{--href="http://handdrawngoods.com/store/tasty-icons-free-food-icons/" target="_blank">handdrawngoods</a>--}}
                {{--</p>--}}
                <p class="text-center to-animate"><a href="#" class="js-gotop">برو بالا</a></p>
                @if(Auth::check())
                    <a href="{{url('/users/dashboard')}}" class="btn btn-primary">صفحه کاربری</a>
                @else
                    <a href="{{url('/users/login')}}" class="btn btn-primary">ورود همکاران</a>
                @endif
                <div class="row" style="margin-top: 15px">
                    <div class="col-md-4"></div>
                    <div class="col-md-2"><p class="text-center to-animate"><a style="font-size: larger" target="_blank"
                                                                               href="{{url('/termsOfUse')}}">قوانین و
                                مقررات
                                سایت</a></p></div>
                    <div class="col-md-2"><p class="text-center to-animate"><a style="font-size: larger" target="_blank"
                                                                               href="{{url('/faq')}}">سوالات
                                متداول</a></p></div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                {{--<ul class="fh5co-social">--}}
                {{--<li class="to-animate-2"><a href="#"><i class="icon-facebook"></i></a></li>--}}
                {{--<li class="to-animate-2"><a href="#"><i class="icon-twitter"></i></a></li>--}}
                {{--<li class="to-animate-2"><a href="#"><i class="icon-instagram"></i></a></li>--}}
                {{--</ul>--}}
                <script src="https://www.zarinpal.com/webservice/TrustCode" type="text/javascript"></script>
            </div>
        </div>
    </div>
</div>

<div id="dialog-processing" title="{{trans('messages.tltDialogProcessing')}}" s>
    <p>{{trans('messages.msgDialogProcessing')}}</p>
</div>


<!-- jQuery -->
<script src="{{URL::to('js/mainPage/jquery.min.js')}}"></script>
<!-- jQuery Easing -->
<script src="{{URL::to('js/mainPage/jquery.easing.1.3.js')}}"></script>
<!-- Bootstrap -->
<script src="{{URL::to('js/mainPage/bootstrap.min.js')}}"></script>
<!-- Bootstrap DateTimePicker -->
<script src="{{URL::to('js/mainPage/moment.js')}}"></script>
<script src="{{URL::to('js/mainPage/bootstrap-datetimepicker.min.js')}}"></script>
<!-- Waypoints -->
<script src="{{URL::to('js/mainPage/jquery.waypoints.min.js')}}"></script>
<!-- Stellar Parallax -->
<script src="{{URL::to('js/mainPage/jquery.stellar.min.js')}}"></script>

<script src="{{URL::to('js/mainPage/sweetalert2.all.min.js')}}"></script>

<script src="{{URL::to('js/mainPage/jquery-ui.js')}}"></script>

<!-- Flexslider -->
<script src="{{URL::to('js/mainPage/jquery.flexslider-min.js')}}"></script>
<script>
    $(function () {
        $('#date').datetimepicker();
    });

    $(document).ready(function () {
        $("#dialog-processing").dialog({
            dialogClass: "no-close",
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            },
        });
    });

    function refreshCaptcha() {

        // alert('ddd');

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
    }

    function saveFeedbackFunction() {

        // alert(id);
        $("#dialog-processing").dialog("open");

        var urlTo = "<?php echo URL::to('/feedbacks/store'); ?>";

        var form_data = new FormData();
        var description = document.getElementById("description").value;
        var email = document.getElementById("email").value;
        var tel = document.getElementById("tel").value;
        var name_and_family = document.getElementById("name_and_family").value;
        var occasionElement = document.getElementById("occasion");
        var title = occasionElement.options[occasionElement.selectedIndex].value;
        // var captcha = document.getElementById("captcha").value;
        var captcha = document.getElementsByName("coinhive-captcha-token")[0].value;


        form_data.append("title", title);
        form_data.append("description", description);
        form_data.append("email", email);
        form_data.append("tel", tel);
        form_data.append("name_and_family", name_and_family);
        // form_data.append("captcha", captcha);
        form_data.append("coinhive-captcha-token", captcha);

        var url = urlTo;

        // alert(url);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: url,
            dataType: 'json',
            type: 'POST',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                $("#dialog-processing").dialog("close");
                // alert('ha!!');
                if (data.success == true) {
                    // alert('success!!');
                    swal({
                        position: 'center',
                        type: 'success',
                        title: '{{trans('messages.msgOperationSuccess')}}',
                    })

                } else if (data.success == false) {
                    // refreshCaptcha();
                    // alert('fail!!'+data);
                    var errorMessage;
                    for (maData in data.messages) {
                        errorMessage = data.messages[maData];

                    }
                    swal({
                        position: 'center',
                        type: 'error',
                        title: '{{trans('messages.msgErrorOperationFail')}}',
                        text: errorMessage
                    })
                }
            },
            error: function (data) {
                // alert('ssde');
                // refreshCaptcha();
                var errorMessage = data.status;
                $("#dialog-processing").dialog("close");
                // if(errorMessage == 'validation.captcha')
                //     alert('ddds');
                // alert('error!!:' + errors);
                for (maData in data.errors) {
                    errorMessage = errorMessage + data.messages[maData];
                }
                swal({
                    position: 'center',
                    type: 'error',
                    title: '{{trans('messages.msgErrorOperationFail')}}',
                    text: errorMessage
                })
            },
            complete: function () {
                // alert('end!!');
            }
        });

    }


</script>
<!-- Main JS -->
<script src="{{URL::to('js/mainPage/main.js')}}"></script>

</body>
</html>