@extends('layouts.new1master_tour')
@section('title')
    <title>شیراز پیک نیک - {{$tour-> title}}</title>
@endsection
@section('head_start')
    <meta name="description" content=" {{strip_tags(\App\Utilities\Utility::GetFirstNChar($tour-> description, 160))  }}"/>

@endsection

@section('page-js-files')
@endsection

@section('content')
    <link href="{{url('quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{url('quill/custom.css')}}" rel="stylesheet">

    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */
    use Illuminate\Support\Facades\Log;
    use Carbon\Carbon;
    use Morilog\Jalali\jDate;
    $is_ended = false;
    $temp_start_date = \Carbon\Carbon::parse($tour->miladi_start_date_time, "Asia/Tehran");
    //    $temp_start_date = \Carbon\Carbon::parse($temp_start_date->toDateString());
    if ($temp_start_date->lt(\Carbon\Carbon::now("Asia/Tehran")))
        $is_ended = true;
    //    $temp_deadline_date_time = new Carbon();
    //    $temp_deadline_date_time->setTimezone("Asia/Tehran");
    $temp_deadline_date_time = \Carbon\Carbon::parse($tour->miladi_deadline_date_time, "Asia/Tehran");
    //    $temp_deadline_date_time->setTimezone("Asia/Tehran");
    //    $temp_deadline_date_time = \Carbon\Carbon::parse($temp_deadline_date_time->toDateTimeString());
    if ($temp_deadline_date_time->lt(\Carbon\Carbon::now("Asia/Tehran")))
        $is_ended = true;

    if($tour->remaining_capacity <= 0)
        $is_ended = true;
    //        Log::info('Carbon::now:'.json_encode(Carbon::now("Asia/Tehran")));
    //        Log::info('miladi_deadline_date_time:'.json_encode($temp_deadline_date_time));

    $wholesaleDiscountIsActive = 0;
    $wholesaleDiscountPercent = 0.0;
    if ($tour->wholesale_discount != null) {
        if ($tour->wholesale_discount->is_active == 1) {
            $wholesaleDiscountIsActive = 1;
        }
        $wholesaleDiscountPercent = $tour->wholesale_discount->percent;
    }
    ?>
    <div>
        <div class="row">
            <div class="col-md-12">
                @if(count($errors) > 0)
                    @foreach($errors as $error)
                        {{--<ul style="color: darkred">--}}
                        {{--<li style="text-align: center" colspan="2">{{$error}}</li>--}}
                        {{--</ul>--}}
                        <script>
                            swal({
                                type: 'error',
                                title: 'خطا',
                                text: '{{$error}}'
                            })
                        </script>
                    @endforeach
                    {{ session()->forget('errors')}}
                @endif
                @if(session('messages'))
                    @foreach (session('messages') as $message)
                        {{--<ul style="color: green">--}}
                        {{--<li style="text-align: center" colspan="2">{{ $message}}</li>--}}
                        {{--</ul>--}}
                        <script>
                            swal({
                                type: 'info',
                                title: 'پیام',
                                text: '{{$message}}'
                            })
                        </script>
                    @endforeach
                    {{ session()->forget('messages')}}
                @endif
            </div>
        </div>
    </div>
    <style>
        .features {
            color: #555;
        }

        label.required:after {
            content: " *";
            color: red;
        }

        /* Popup container - can be anything you want */
        .popups {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .Popups {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* The actual popup */
        .popups .popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #6b63e4;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        .Popups .Popuptext {
            visibility: hidden;
            width: 160px;
            background-color: #6b63e4;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -80px;
        }

        /* Popup arrow */
        .popups .popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        .Popups .Popuptext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class - hide and show the popup */
        .popups .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }

        .Popups .show {
            visibility: visible;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>



    <!--================ Start Home Banner Area =================-->
    <section class="home_banner_area common-banner">
        <div class="banner_inner">
            <div class="container-fluid no-padding">
                <div class="row" style="height: 540px !important;">
                    <style>
                        .home_banner_area {
                            background: url({{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/1/3.33')}}) no-repeat scroll center center;
                            background-size: cover;
                        }
                    </style>
                </div>
            </div>
        </div>
    </section>
    <!-- Start banner bottom -->
    <div class="row banner-bottom common-bottom-banner align-items-center justify-content-center">
        <div class="col-lg-8 offset-lg-4 alpha01" style="margin-top: 200px;">
            <div class="banner_content">
                <div class="row d-flex align-items-center" style="direction: rtl;">
                    <div class="col-lg-7 col-md-12" style="direction: rtl;">
                        <h1>{!! $tour->title !!}</h1>
                        <p>
                        @if(!is_array($tour->tour_address->address))
                            <!-- for old version tours-->
                                {{$tour->tour_address->address}}
                            @else

                                <?php
                                $i=0;
                                $count = count($tour->tour_address->address);
                                ?>
                                @foreach($tour->tour_address->address as $address)
                                    @if($count > 1 && $i != $count-1)
                                        {{$address->address."-"}}
                                    @else
                                        {{$address->address}}
                                    @endif

                                    <?php $i++ ?>
                                @endforeach
                            @endif

                            {{--{!! $tour->tour_address->address !!}--}}
                        </p>
                    </div>
                    <div class="col-lg-5 col-md-12">
                        <div class="page-link-wrap">
                            <div class="page_link">
                                <a href="#reserve_form">
                                    <span style="font-size: 20px;">رزرو (</span>
                                    @if($tour->cost == 0)
                                        رایگان
                                    @else
                                        @if($tour->stroked_cost != null && $tour->stroked_cost > 0)
                                            <del>{{number_format($tour->stroked_cost)}} </del>
                                            &nbsp;  {{number_format($tour->cost)}} تومان
                                        @else
                                            {{number_format($tour->cost)}} تومان
                                        @endif
                                    @endif
                                    <span style="font-size: 20px;">)</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End banner bottom -->
    <!--================ End Home Banner Area =================-->
    <form class="col-md-12" name="reserve_form" role="form" method="POST"
          action="{{ url('/transactions/storeTour') }}">
        <!--================Blog Area =================-->
        <section class="blog_area single-post-area section_gap">
            <div class="container">
                <div class="row" style="margin-top: 40px">
                    <div class="col-lg-8 posts-list">
                        <div class="single-post row">
                            <div class="col-lg-12" id="gallery_outBtn">
                                <div class="feature-img">
                                    <div class="fotorama about-image" data-allowfullscreen="native" data-nav="thumbs">
                                        @foreach($pictures as $picture)
                                            <img class="img-fluid"
                                                 src="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/'.$picture->media_id.'/3.22')}}" alt="شیراز پیک نیک -  {{$tour->title}}">
                                        @endforeach
                                        @if(count($films)>0)
                                            @foreach($films as $video)
                                                @if($video->link != null)
                                                    {{--<iframe  width="100%" height="100%" poster="{{url('/images/film_thumb.jpg')}}" src="{!! $video->link !!}" data-video="true"--}}
                                                    {{--data-img="{{url('/images/film_thumb.jpg')}}">--}}
                                                    {{--</iframe>--}}
                                                    {{--<a href="{!! $video->link !!}"--}}
                                                    {{--data-video="true"--}}
                                                    {{--data-img="{{url('/images/film_thumb.jpg')}}">--}}
                                                    {{--</a>--}}
                                                    <a href="{!! $video->link !!}"
                                                       data-video="true"
                                                       data-img="{{url('/images/film_thumb.jpg')}}">
                                                        <img src="{{url('/images/film_thumb.jpg')}}" alt="شیراز پیک نیک -  {{$tour->title}}">
                                                    </a>
                                                @else
                                                    {{--<video width="100%" height="100%" poster="{{url('/images/film_thumb.jpg')}}" data-img="{{url('/images/film_thumb.jpg')}}" controls>--}}
                                                    {{--<source  type="{{$video->mime_type}}">--}}
                                                    {{--{{trans('messages.msgErrorNoVideoSupport')}}--}}
                                                    {{--</video>--}}
                                                    <a href="{{URL::to('tours/getFileStream/'.$tour->tour_id.'/'.$tour->tour_guid.'/'.$video->media_id.'/'.\App\Tag::TAG_TOUR_CLIP_DOWNLOAD)}}"
                                                       data-video="true"
                                                       data-img="{{url('/images/film_thumb.jpg')}}">
                                                        <img src="{{url('/images/film_thumb.jpg')}}" alt="شیراز پیک نیک -  {{$tour->title}}">
                                                    </a>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    {{--<img class="img-fluid" src="{{URL::to('../img/blog/feature-img1.jpg')}}" alt="">--}}
                                </div>
                            </div>


                            <div class="col-lg-5  col-md-5">
                                <div class="blog_info text-right">
                                    <ul class="blog_meta list">
                                        <li>
                                            <span style="color: black;padding-right: 20px;">شرکت:</span>{{$info->company}}
                                            <i
                                                    class="lnr lnr-home"></i></li>
                                        <li>
                                            <span style="color: black;padding-right: 20px;">شماره تماس:</span>{{$info->coordination_tel}}
                                            <i class="lnr lnr-phone"></i></li>
                                    </ul>
                                    <br>
                                    <ul class="blog_meta list">
                                        <li><span style="color: black;padding-right: 20px;">تاریخ حرکت:</span>
                                            <?php
                                            $ip = $tour->start_date_time; // some IP address
                                            $iparr = explode(" ", $ip);
                                            echo($iparr[0]);
                                            ?><i class="lnr lnr-clock"></i></li>
                                        <li><span style="color: black;padding-right: 35px;">ساعت:</span>
                                            <?php
                                            $ip = $tour->start_date_time; // some IP address
                                            $iparr = explode(" ", $ip);
                                            echo($iparr[1]);
                                            ?></li>
                                        <li><span style="color: black;padding-right: 20px;">تاریخ بازگشت:</span><?php
                                            $ip = $tour->end_date_time; // تاریخ بازگشت
                                            $iparr = explode(" ", $ip);
                                            echo($iparr[0]);
                                            ?><i class="lnr lnr-clock"></i></li>
                                        <li><span style="color: black;padding-right: 35px;">ساعت:</span>
                                            <?php
                                            $ip = $tour->end_date_time; // ساعت بازگشت
                                            $iparr = explode(" ", $ip);
                                            echo($iparr[1]);
                                            ?></li>
                                    </ul>
                                    <br>
                                    <ul class="blog_meta list">
                                        <li>
                                            <span style="color: black;padding-right: 20px;">درجه سختی:</span>{{\App\Tour::getHardshipLevelStringByCode($tour->hardship_level) }}
                                            <i class="lnr lnr-thumbs-up"></i>
                                        </li>
                                        <li>
                                            <span style="color: black;padding-right: 20px;">ظرفیت کل:</span>{{$tour->total_capacity}}
                                            نفر <i class="lnr lnr-pie-chart"></i></li>
                                    </ul>
                                    <br>
                                    <ul class="blog_meta list">
                                        <li>
                                            <span style="color: black;padding-right: 20px;">جنسیت:</span>{{ \App\Tour::getGenderStringByCode($tour->gender)}}
                                            <i class="lnr lnr-user"></i></li>
                                        <li>
                                            <span style="color: black;padding-right: 35px;">حداقل سن:</span>{{$tour->minimum_age}}
                                            سال
                                        </li>
                                        <li>
                                            <span style="color: black;padding-right: 35px;">حداکثر سن:</span> {{$tour->maximum_age}}
                                            سال
                                        </li>
                                    </ul>
                                    <br>
                                    <ul class="blog_meta list">
                                        <?php
                                        $deadline = $tour->deadline_date_time; // تاریخ مهلت ثبت نام
                                        $deadline = explode(" ", $deadline);

                                        $deadline_date = $deadline[0];
                                        $deadline_hour = $deadline[1];
                                        ?>
                                        <li>
                                            <span style="color: red;padding-right: 20px;">مهلت ثبت نام:</span>{{$deadline_date}}
                                            <i class="lnr lnr-hourglass"></i></li>
                                        <li><span style="color: black;padding-right: 35px;">ساعت:</span>
                                            {{$deadline_hour}}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 blog_details" id="sharh_outBtn">
                                <h2>شرح برنامه</h2>
                                <p class="excert">
                                    {!! $tour-> description !!}
                                </p>
                            </div>
                            <div class="col-lg-12">
                                <div class="quotes" style="text-align: right;direction: rtl">
                                    <h4>قوانین و شرایط</h4>
                                    {{$info-> regulations}}
                                </div>
                                <div class="row" id="ja_outBtn">
                                    <div class="col-6">
                                        <label style="text-align: center;width: 100%;">
                                            مقصد:

                                        @if(!is_array($tour->tour_address->address))
                                            <!-- for old version tours-->
                                                {{$tour->tour_address->address}}
                                            @else

                                                <?php
                                                $i=0;
                                                $count = count($tour->tour_address->address);
                                                ?>
                                                @foreach($tour->tour_address->address as $address)
                                                    @if($count > 1 && $i != $count-1)
                                                        {{$address->address."-"}}
                                                    @else
                                                        {{$address->address}}
                                                    @endif

                                                    <?php $i++ ?>
                                                @endforeach
                                            @endif
{{--                                            {!! $tour->tour_address->address !!}--}}


                                        </label>
                                        <div class="about-image">
                                            <div id="map"
                                                 style="border: 1px solid #a2959530;border-radius: 2px;width:auto;height:262px;display: block;margin-right: auto;margin-left: auto">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label style="text-align: center;width: 100%;">مبدا:
                                        @if(!is_array($tour->gathering_place->address))
                                            <!-- for old version tours-->
                                                {{$tour->gathering_place->address}}
                                            @else
                                                <?php
                                                $i=0;
                                                $count = count($tour->gathering_place->address);
                                                ?>
                                                @foreach($tour->gathering_place->address as $address)
                                                        <?php
//                                                        $gathering_place_start_date_time = $address->start_date_time;
//                                                        $gathering_place_start_date_time = explode(" ", $gathering_place_start_date_time);
//
//                                                        $gathering_place_start_date_time_hour = $gathering_place_start_date_time[1];

                                                        $dt = DateTime::createFromFormat("Y-m-d H:i:s", $address->start_date_time);
                                                        $hours = $dt->format('H:i'); // '20'
                                                        ?>
                                                    @if($count > 1 && $i != $count-1)
                                                        {{$address->address."(".$hours.")"."-"}}
                                                    @else
                                                        {{$address->address."(".$hours.")"}}
                                                    @endif

                                                    <?php $i++ ?>
                                                @endforeach
                                            @endif
                                        </label>
{{--                                            {{$tour->gathering_place->address}} </label>--}}
                                        <div class="about-image">
                                            <div id="originMap"
                                                 style="border: 1px solid #a2959530;border-radius: 2px;width:auto;height:262px;display: block;margin-right: auto;margin-left: auto">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>


                        <!--================ Start Reservstion Area =================-->

                        <section class="reservation-area section_gap_top">
                            <style>
                                .reservation-area {
                                    background: url({{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/'.$pictures[1]->media_id.'/3.22')}}) center no-repeat;
                                }
                            </style>
                            <div class="container" id="reserve_form">
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-lg-7 offset-lg-3" style="margin-top:40px">
                                        <div class="contact-form-section">
                                            <h2>اطلاعات رزرو کننده</h2>

                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" id="name_and_family_0"
                                                       name="name_and_family_0"
                                                       placeholder="نام و نام خانوادگی (اجباری)" onfocus="this.placeholder = ''"
                                                       onblur="this.placeholder = 'لطفا نام و نام خانوادگی خود را وارد کنید!'"
                                                       value="{{ old('name_and_family') }}"
                                                       required>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" id="national_code_0"
                                                       name="national_code_0"
                                                       placeholder="کد ملی (اجباری)" onfocus="this.placeholder = ''"
                                                       onblur="this.placeholder = 'وارد کردن کد ملی ضروری است'"
                                                       value="{{ old('national_code') }}"
                                                       required>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" id="mobile_0" name="mobile_0"
                                                       placeholder="شماره تلفن همراه (اجباری)" onfocus="this.placeholder = ''"
                                                       onblur="this.placeholder = 'لطفا شماره همراه خود را وارد کنید!'"
                                                       value="{{ old('mobile') }}"
                                                       required>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control initial-value-example"
                                                       id="start_date_show_0"
                                                       placeholder="تاریخ تولد (اجباری)" onfocus="this.placeholder = ''"
                                                       value="{{ old('start_date_show') }}"
                                                       required autocomplete="off" onkeypress="return false;">
                                                <input id="birth_date_0"
                                                       name="birth_date_0"
                                                       style="display: none">
                                            </div>
                                            <div class="form-group col-md-12">
                                                {{--<div class="row"  style="direction: rtl">--}}
                                                    {{--<div class="col-md-3">--}}
                                                        {{--<label class="align-items-end"> محل تجمع:</label>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-md-9">--}}
                                                        <select  id="start_address_0" name="start_address_0" class="form-control initial-value-example" required>
                                                            @if(!is_array($tour->gathering_place->address))
                                                            <!-- for old version tours-->
                                                                <option selected="selected" readonly="true" value="{{$tour->gathering_place->address}}">  محل تجمع:&nbsp;{{$tour->gathering_place->address}}</option>
                                                            @else
                                                                    <option  selected="true" value="" >محل تجمع (اجباری)</option>
                                                                @foreach($tour->gathering_place->address as $gathering_address)
                                                                    @if(count($tour->gathering_place->address) == 1)
                                                                            <option value='{{$gathering_address -> address}}' selected>{{$gathering_address -> address}}</option>
                                                                        @else
                                                                            <option value='{{$gathering_address -> address}}'>{{$gathering_address -> address}}</option>
                                                                        @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    {{--</div>--}}
                                                {{--</div>--}}






                                            </div>
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" id="email_0" name="email_0"
                                                       placeholder="رایانامه(اختیاری)" onfocus="this.placeholder = ''"
                                                       onblur="this.placeholder = 'رایانامه(اختیاری)'"
                                                       value="{{ old('email') }}">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <textarea class="form-control" id="personal_description_0"
                                                          name="personal_description_0"
                                                          placeholder="بیماری های خاص، دیابت، حساسیت، فشار خون و ...(اختیاری و در صورت لزوم)">{{ old('personal_description') }}</textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!--================ End Reservstion Area =================-->
                    </div>
                    <div class="col-lg-4">
                        <div class="blog_right_sidebar">
                            <aside class="single_sidebar_widget author_widget">
                                <h3 style="text-align: center;color: #949494">لیدرهای این برنامه</h3>
                                <br>
                                @foreach($leaders as $leader)
                                    <img class="author_img rounded-circle"
                                         src="{{URL::to('users/getFile/'.$leader->user_id.'/'.$leader->user_guid.'/1.11')}}"
                                         alt="شیراز پیک نیک - {{$leader->name_family}} - {{$tour->title}}" style="max-width: 120px;">
                                    <h4>{{$leader->name_family}}</h4>
                                    <div class="social_icon">
                                        {{--<p>Senior blog writer</p>--}}
                                        @if(isset($leader->info->show_social) && $leader->info->show_social)
                                            @if(isset($leader->social))
                                                @foreach($leader->social as $social)
                                                    @if($social->code == \App\Tour::SocialInstagram)
                                                        <a target="_blank" href="{!! $social->address !!}"><i
                                                                    class="fa fa-instagram" title="اینستاگرام"></i></a>
                                                    @elseif($social->code == \App\Tour::SocialFacebook)
                                                        <a target="_blank" href="{!! $social->address !!}"><i
                                                                    class="fa fa-facebook" title="فیس بوک">></i></a>
                                                    @elseif($social->code == \App\Tour::SocialTelegram)
                                                        <a target="_blank" href="{!! $social->address !!}"><i
                                                                    class="fa fa-telegram" title="تلگرام"></i></a>
                                                    @elseif($social->code == \App\Tour::SocialTwitter)
                                                        <a target="_blank" href="{!! $social->address !!}"><i
                                                                    class="fa fa-twitter" title="تویتر"></i></a>
                                                    @else
                                                        <a target="_blank" href="{!! $social->address !!}"><i
                                                                    class="fa fa-user" title=""></i></a>
                                                    @endif
                                                @endforeach
                                            @endif

                                            {{--<div class="social_icon">--}}
                                            {{--<a href="#"><i class="fa fa-facebook"></i></a>--}}
                                            {{--<a href="#"><i class="fa fa-twitter"></i></a>--}}
                                            {{--<a href="#"><i class="fa fa-github"></i></a>--}}
                                            {{--<a href="#"><i class="fa fa-behance"></i></a>--}}
                                            {{--</div>--}}
                                        @endif
                                        {{--<p>Boot camps have its supporters andit sdetractors. Some people do not understand why--}}
                                        {{--you--}}
                                        {{--should have to spend money on boot camp when you can get. Boot camps have itssuppor--}}
                                        {{--ters andits detractors.</p>--}}
                                    </div>
                                    <div class="br"></div>
                                @endforeach
                            </aside>
                            <aside class="single_sidebar_widget post_category_widget" id="ser_outBtn">
                                <h3 class="widget_title">خدمات تور</h3>
                                @if(isset($features))
                                    @if(count($features) > 0)
                                        <div class="alert alert-success">
                                            <i class="fa fa-check-circle"></i>
                                            موارد زیر برای شما فراهم خواهد بود.
                                        </div>
                                        <?php
                                        $is_exist = false;
                                        ?>
                                        <ul class="list cat-list">
                                            @foreach($features as $feature)
                                                @if(($feature->is_required == 0) && ($feature->is_optional == 0))
                                                    <?php
                                                    $is_exist = true;
                                                    ?>
                                                    <li>
                                                        <a class="justify-content-between">
                                                            <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}"
                                                                 width="30px" alt="شیراز پیک نیک -  {{$tour->title}}"
                                                                 title="{{$feature->name}}">
                                                            @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                                                <h3 style="float: right;">{{$feature->name}}</h3>
                                                                <div class="popups"
                                                                     onmouseover="showDetail({{$feature->feature_uid}})"
                                                                     onmouseout="HideDetail({{$feature->feature_uid}})">
                                                                    <sup> <a class="fa fa-question-circle"
                                                                             aria-hidden="true"
                                                                             style="color: #6b63e4"></a>
                                                                    </sup>
                                                                    <span class="popuptext"
                                                                          id="myPopup_{{$feature->feature_uid}}">
                                                                    <p style="text-align: center">{!! $feature->description." ".$feature->more_description !!}
                                                                    </p>
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <h3>{{$feature->name}}</h3>
                                                                <span id="myPopup_{{$feature->feature_uid}}"></span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        @if(!$is_exist)
                                            <h5 style="color: black;margin-top: 5px;text-align: center;">موردی وجود ندارد</h5>
                                        @endif
                                        {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                        {{--<form class="row" name="reserve_form" role="form" method="POST"--}}
                                        {{--onsubmit="return validateMyForm();"--}}
                                        {{--                              action="{{ url('/transactions/storeTour') }}">--}}
                                        {{--                        {{ csrf_field() }}--}}
                                        {{--//هانی پوت--}}
                                        <div style="display: none;">
                                            <label>این فرم را خالی بگذارید</label>
                                            <input type="text" name="phone" id="phone"/>
                                        </div>
                                        <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                                    @else
                                        <h5 style="color: black;margin-top: 5px;text-align: center;">موردی وجود ندارد</h5>
                                        <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                                    @endif
                                @else
                                    <h5 style="color: black;margin-top: 5px;text-align: center;">موردی وجود ندارد</h5>
                                    <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                                @endif
                                <div class="br"></div>
                            </aside>
                            <aside class="single_sidebar_widget post_category_widget">
                                <h3 class="widget_title">وسایل مورد نیاز</h3>
                                @if(isset($features))
                                    @if(count($features) > 0)
                                        <div class="alert alert-warning">
                                            برای همراهی با تور بایستی موارد زیر را همراه داشته باشید.
                                        </div>
                                        <?php
                                        $is_exist = false;
                                        ?>
                                        <ul class="list cat-list">
                                            @foreach($features as $feature)
                                                @if($feature->is_required == 1)
                                                    <?php
                                                    $is_exist = true;
                                                    ?>
                                                    <li>
                                                        <a class="justify-content-between">
                                                            <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}"
                                                                 width="30px" alt="شیراز پیک نیک -  {{$tour->title}}"
                                                                 style="vertical-align: middle;float: right"
                                                                 title="{{$feature->name}}">
                                                            @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                                                <h3 style="float: right">{{$feature->name}}</h3>
                                                                <div class="popups"
                                                                     onmouseover="showDetail({{$feature->feature_uid}})"
                                                                     onmouseout="HideDetail({{$feature->feature_uid}})">
                                                                    <sup> <a class="fa fa-question-circle"
                                                                             aria-hidden="true"
                                                                             style="color: #6b63e4"></a>
                                                                    </sup>
                                                                    <span class="popuptext"
                                                                          id="myPopup_{{$feature->feature_uid}}">
                                                                    <p style="text-align: center">{!! $feature->description." ".$feature->more_description !!}
                                                                    </p>
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <h3>{{$feature->name}}</h3>
                                                                <span id="myPopup_{{$feature->feature_uid}}"></span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        @if(!$is_exist)
                                            <h5 style="color: black;margin-top: 5px;text-align: center;">موردی وجود ندارد</h5>
                                        @endif
                                        {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                        {{--<form class="row" name="reserve_form" role="form" method="POST"--}}
                                        {{--onsubmit="return validateMyForm();"--}}
                                        {{--                              action="{{ url('/transactions/storeTour') }}">--}}
                                        {{--                        {{ csrf_field() }}--}}
                                        {{--//هانی پوت--}}
                                        <div style="display: none;">
                                            <label>این فرم را خالی بگذارید</label>
                                            <input type="text" name="phone" id="phone"/>
                                        </div>
                                        <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                                    @else
                                        <h5 style="color: black;margin-top: 5px;text-align: center;">موردی وجود ندارد</h5>
                                        <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                                    @endif
                                @else
                                    <h5 style="color: black;margin-top: 5px;text-align: center;">موردی وجود ندارد</h5>
                                    <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                                @endif
                                <div class="br"></div>
                            </aside>
                            <aside class="single-sidebar-widget newsletter_widget">
                                <h4 class="widget_title">درباره ی مقصد</h4>
                                <a href="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/'.$pictures[2]->media_id.'/3.22')}}"><img
                                            class="img-fluid"
                                            src="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/'.$pictures[2]->media_id.'/3.22')}}"
                                            alt="شیراز پیک نیک -  {{$tour->title}}"></a>
                                <div class="br"></div>
                                <h4>
                                @if(!is_array($tour->tour_address->address))
                                    <!-- for old version tours-->
                                        {{$tour->tour_address->address}}
                                    @else

                                        <?php
                                        $i=0;
                                        $count = count($tour->tour_address->address);
                                        ?>
                                        @foreach($tour->tour_address->address as $address)
                                            @if($count > 1 && $i != $count-1)
                                                {{$address->address."-"}}
                                            @else
                                                {{$address->address}}
                                            @endif

                                            <?php $i++ ?>
                                        @endforeach
                                    @endif
{{--                                    {!! $tour->tour_address->address !!}--}}
                                </h4>
                                <p>
                                    {!! $tour->tour_address->description !!}
                                </p>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================Blog Area =================-->
        <section class="menu_area section_gap" id="extser_outBtn">
            <div class="container">
                <h2 class="mb-30 title_color" style="text-align: center;">خدمات اضافه</h2>
                <div class="row menu_inner">
                    <div class="col-lg-6 box_01a" style="margin-top: 20px;">
                        <div class="menu_list">
                            <h4 class="mb-20" style="text-align: center">اضافه کردن همراه</h4>

                            @if($wholesaleDiscountIsActive == 1)
                                <div class="alert alert-success">
                                    در صورت اضافه کردن هر یک همراه شما {{$wholesaleDiscountPercent}} درصد تخفیف خواهید
                                    گرفت.
                                </div>
                            @endif

                            <div>
                                <a onclick="Add();" class="genric-btn success arrow radius"
                                   style="display: block;margin: 0px auto;font-size: 20px">اضافه کردن یک همراه<span
                                            class="lnr lnr-plus-circle"></span></a>
                            </div>
                            <div id="entourage"></div>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1 box_01a" style="margin-top: 20px;background-color: #dfeeef">
                        <div class="menu_list">
                            <h4 class="mb-20" style="text-align: center">امکانات اختیاری</h4>
                            <div class="alert alert-primary">
                                مواردی که در صورت درخواست کاربر تامین می شوند.
                            </div>
                            @if(isset($features))
                                @if(count($features) > 0)
                                    <ul class="list" id="buyableFeatrues">
                                        <?php
                                        $buyableCount = 0;
                                        ?>
                                        @foreach($features as $feature)
                                            @if($feature->is_optional == 1)
                                                <?php
                                                $remainingCapacity = $feature->capacity - $feature->count;
                                                ?>
                                                <li id="buyableFeatrue_{{$buyableCount}}">
                                                    <input type="text" id="buyable_{{$buyableCount}}"
                                                           name="buyable_{{$buyableCount}}"
                                                           value="{{$feature->feature_uid}}"
                                                           style="display: none">
                                                    <input type="text"
                                                           id="buyable_cost_{{$buyableCount}}"
                                                           value="{{$feature->cost}}"
                                                           style="display: none">
                                                    <h4>
                                                        @if(strcmp($remainingCapacity,'0')  == 0)
                                                            <input type="checkbox"
                                                                   name="chk_{{$feature->feature_uid}}"
                                                                   id="chk_{{$feature->feature_uid}}"
                                                                   onchange="calculateCost()"
                                                                   style="width: 20px; height: 20px;margin-left: 5px;pointer-events:none;"
                                                                   disabled>
                                                        @else
                                                            <input type="checkbox"
                                                                   name="chk_{{$feature->feature_uid}}"
                                                                   id="chk_{{$feature->feature_uid}}"
                                                                   onchange="calculateCost()"
                                                                   style="width: 20px; height: 20px;margin-left: 5px">
                                                        @endif
                                                        <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}"
                                                             width="30px" alt="شیراز پیک نیک -  {{$tour->title}}"
                                                             style="vertical-align: middle;margin-left:0px;"
                                                             title="{{$feature->name}}">
                                                        {{$feature->name}}
                                                        <span style="margin-right: 10px;">
                                                        @if(strcmp($remainingCapacity,'0')  == 0)
                                                                <input id="quantity_{{$feature->feature_uid}}"
                                                                       type="number"
                                                                       name="quantity_{{$feature->feature_uid}}"
                                                                       min="1"
                                                                       max="{{$remainingCapacity}}"
                                                                       min="1"
                                                                       style="width: 35px;height: 20px"
                                                                       onchange="calculateCost()"
                                                                       value="1"
                                                                       style="pointer-events:none;border: 1px solid silver;"
                                                                       disabled>
                                                            @else
                                                                <input id="quantity_{{$feature->feature_uid}}"
                                                                       type="number"
                                                                       name="quantity_{{$feature->feature_uid}}"
                                                                       min="1"
                                                                       max="{{$remainingCapacity}}"
                                                                       min="1"
                                                                       style="width: 35px;height: 20px;border: 1px solid silver;"
                                                                       onchange="calculateCost()"
                                                                       value="1">
                                                            @endif
                                                    </span>



                                                        <span>
                                                          @if($feature->cost == 0)
                                                                رایگان
                                                            @else
                                                                {{number_format($feature->cost)}} تومان
                                                            @endif

                                                        </span>

                                                        @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                                            <div class="popups"
                                                                 onmouseover="showDetailOptional({{$feature->feature_uid}})"
                                                                 onmouseout="HideDetailOptional({{$feature->feature_uid}})">
                                                                <sup> <a class="fa fa-question-circle"
                                                                         aria-hidden="true"
                                                                         style="color: #6b63e4"></a>
                                                                </sup>
                                                                <span class="popuptext"
                                                                      id="myPopupOptional_{{$feature->feature_uid}}">
                                                                                        <p>{!! $feature->description." ".$feature->more_description." ظرفیت باقی مانده : ".$remainingCapacity  !!}</p>
                                                                                </span>
                                                            </div>
                                                        @else
                                                            <span id="myPopupOptional_{{$feature->feature_uid}}"></span>
                                                        @endif
                                                    </h4>
                                                </li>
                                                <?php
                                                $buyableCount++;
                                                ?>
                                            @endif
                                        @endforeach
                                    </ul>
                                    <div class="col-md-12 fe02" dir="rtl" id="featuresListIcons">
                                        @if($buyableCount == 0)
                                            <h5 style="color: black;margin-top: 5px">موردی وجود
                                                ندارد</h5>
                                        @endif
                                        <br>
                                    </div>

                                @else
                                    <h5 style="color: black;margin-top: 5px;text-align: center;">موردی وجود ندارد</h5>
                                @endif

                            @else
                                <h5 style="color: black;margin-top: 5px;text-align: center;">موردی وجود ندارد</h5>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAIN CONTENT -->

        <div class="fluid-container">
            <div class="content-wrapper">
                <div class="page-section" id="projects" dir="rtl">
                    <div class="row" dir="rtl">
                        {{--<form class="col-md-12" name="reserve_form" role="form" method="POST"--}}
                        {{--onsubmit="return validateMyForm();"--}}
                        {{--action="{{ url('/transactions/storeTour') }}">--}}
                        {{ csrf_field() }}
                        <div style="display: none;">
                            <label>این فرم را خالی بگذارید</label>
                            <input type="text" name="phone" id="phone"/>
                        </div>
                        <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">


                        <div>

                            <!-- CONTACT -->
                            <div class="page-section" id="contact">
                                <div id="MeysamTemp" style="margin-top: 20px">
                                    <div>
                                        <fieldset style="padding-top: 30px" id="contact2">
                                            <div class="row form-group">
                                                <div class="col-md-12" style="margin-top: 20px;">
                                                    <div class="col-md-12 form-group">
                                                        <div class="alert alert-warning">
                                                            فردی که مشخصات او در این فرم به عنوان
                                                            رزرو کننده ثبت
                                                            شده بایستی
                                                            برای
                                                            اعزام به مقصد حضور داشته باشد و کارت ملی
                                                            خود و تمامی
                                                            افراد ثبت
                                                            شده
                                                            را نیز به همراه داشته
                                                            باشد.
                                                        </div>
                                                        <div class="alert alert-danger">
                                                            با فشردن دکمه ثبت و پرداخت شما متعهد به
                                                            رعایت همه ی
                                                            قوانین
                                                            اخلاقی و
                                                            شرعی و عرفی جمهوری اسلامی ایران خواهید
                                                            بود و مسئولیت
                                                            هرگونه
                                                            مشکلی که
                                                            در اثر رعایت نکردن این قوانین به وجود
                                                            بیاید به عهده
                                                            خود شما می
                                                            باشد.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 form-group"
                                                         id="captchSection">
                                                        <p style="text-align: center;font-size: 18px;background-color: #dfeeef">
                                                            <input type="checkbox" id="chk_terms"
                                                                   name="chk_terms" required>
                                                            تایید می کنم که
                                                            <a target="_blank"
                                                               href="{{url('/termsOfUse') }}">
                                                                شرایط
                                                                و
                                                                قوانین </a> استفاده از سایت را
                                                            پذیرفته ام.
                                                        </p>
                                                    </div>
                                                    <div>
                                                        @if(!$is_ended && ($tour->cost > 0))
                                                            <div class="row" id="test"
                                                                 style="alignment: center;margin-top: 10px;margin-bottom: 10px">
                                                                <div class="col-md-12"
                                                                     style="text-align: center;font-size: 24px">
                                                                    <span style="margin-left: 5px">  قیمت نهایی:</span>

                                                                    <span id="final_cost"
                                                                          style="font-weight: 600;"> {{$tour->cost}}</span>
                                                                    <div class="mt-10">
                                                                        <input type="text" name="discount_code"
                                                                               placeholder="کد تخفیف"
                                                                               class="single-input col-md-3 center"
                                                                               style="width: 90%;margin: 0px auto;border: 1px solid #7f7f7f;">
                                                                    </div>
                                                                    {{--<span>تومان</span>--}}
                                                                </div>
                                                            </div>


                                                            <button type="submit"
                                                                    class="btn btn-primary" onclick="checkStartPlace()"
                                                                    style="min-width:30%;display: block;margin-left: auto;margin-right: auto;">
                                                                ثبت و پرداخت
                                                            </button>
                                                        @else
                                                            @if($is_ended)
                                                                <div class="row" id="test"
                                                                     style="alignment: center;margin-top: 10px;margin-bottom: 10px">
                                                                    <div class="col-md-12"
                                                                         style="text-align: center;font-size: large">
                                                                        <span style="margin-left: 5px"> ثبت نام به پایان رسیده است     </span>

                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="row" id="test"
                                                                     style="alignment: center;margin-top: 10px;margin-bottom: 10px">
                                                                    <div class="col-md-12"
                                                                         style="text-align: center;font-size: large">
                                                                                            <span id="free"
                                                                                                  style="margin-left: 5px">رایگان</span>
                                                                        <span id="final_cost"></span>

                                                                    </div>
                                                                </div>


                                                                <button type="submit"
                                                                        class="btn btn-primary"
                                                                        style="min-width:30%;display: block;margin-left: auto;margin-right: auto;">
                                                                    ثبت
                                                                </button>
                                                            @endif



                                                        @endif

                                                        {{--<br>--}}
                                                        {{--<a href="{{url('/')}}"--}}
                                                        {{--class="btn btn-primary"--}}
                                                        {{--style="max-width:30%;display: block;margin-left: auto;margin-right: auto">  {{trans('messages.btnBack')}} </a>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <script>
                                            // document.getElementById("final_cost").innerHTML = calculateCost();


                                            var person_count = 1;

                                            function Add() {
                                                comeradeCount++;
                                                person_count = person_count + 1;
                                                calculateCost();
                                                // if (document.getElementById("final_cost"))
                                                //     document.getElementById("final_cost").innerHTML = calculateCost();

                                                var count = $("#entourage > div").length;
                                                count++;//for main person
                                                var maxCount = "<?php echo $tour->remaining_capacity ?>";
                                                if (count == maxCount) {
                                                    swal({
                                                        position: 'center',
                                                        type: 'error',
                                                        title: '{{trans('messages.msgMaxCountReached')}}',
                                                    })
                                                    return;
                                                }
                                                persId = "persInfo_" + count;
                                                name_and_familyId = "name_and_family_" + count;
                                                national_codeId = "national_code_" + count;
                                                mobileId = "mobile_" + count;
                                                birth_dateId = "birth_date_" + count;
                                                emailId = "email_" + count;
                                                btnId = "btn_" + count;
                                                test = "test_" + count;
                                                name_and_family_name = "name_and_family_" + count;
                                                national_code_name = "national_code_" + count;
                                                mobile_name = "mobile_" + count;
                                                birth_date_name = "birth_date_" + count;
                                                email_name = "email_" + count;
                                                start_date_show = "start_date_show_" + count;
                                                personal_description_name = "personal_description_" + count;
                                                start_address_id = "start_address_" + count;


                                                var Input = " <div class=\"col-md-6 comment-form\" id='" + persId + "' style=\"float: right\" >" +
                                                    "<fieldset>" +
                                                    "<legend>  اطلاعات همراه" + count + "</legend> <div class=\"form-group\">\n" +
                                                    " <label class=\"required\"\n" +
                                                    "  for=\"name_and_family\">  {{trans('messages.lblNameAndFamily')}}\n" +
                                                    "\n" +
                                                    "   </label>\n" +
                                                    "  <div >\n" +
                                                    "    <input  id='" + name_and_familyId + "' type=\"text\"\n" +
                                                    "     name='" + name_and_family_name + "'\n" +
                                                    "      title=\"نام و نام خانوادگی را وارد کنید.  \"\n" +
                                                    "      value=\"{{ old('name_and_family') }}\"\n" +
                                                    "      required>\n" +
                                                    "       </div>\n" +
                                                    "       </div><div class=\"form-group\">\n" +
                                                    "        <label class=\"required\"\n" +
                                                    "         for=\"national_code\">  {{trans('messages.lblNationalCode')}}</label>\n" +
                                                    "         <div>\n" +
                                                    "         <input id='" + national_codeId + "' type=\"text\" name='" + national_code_name + "'\n" +
                                                    "           title=\"کد ملی را وارد کنید.\"\n" +
                                                    "            value=\"{{ old('national_code') }}\"\n" +
                                                    "             required>\n" +
                                                    "             </div>\n" +
                                                    "            </div><div class=\"form-group\">\n" +
                                                    "             <label class=\"norequired\"\n" +
                                                    "              for=\"mobile\"> {{trans('messages.lblMobile')}}</label>\n" +
                                                    "              <div>\n" +
                                                    "                <input id='" + mobileId + "' type=\"text\" name='" + mobile_name + "'\n" +
                                                    "                title=\"شماره تلفن همراه را وارد کنید.\"\n" +
                                                    "                 value=\"{{ old('mobile') }}\"\n" +
                                                    "                >\n" +
                                                    "                </div>\n" +
                                                    "                </div>\n" +
                                                    "                 <div class=\"form-group\">\n" +
                                                    "                  <label class=\"required\"\n" +
                                                    "                    for=\"birth_date\">    {{trans('messages.lblBirthDate')}}</label>\n" +
                                                    "\n" +
                                                    "                  <div>\n" +

                                                    "    <input id='" + start_date_show + "'  type=\"text\" \n" +
                                                    "     class=\"initial-value-example\"\n" +
                                                    "autocomplete=\"off\" \n" +
                                                    "        required title=\" تاریخ تولد را مشخص کنید\">\n" +
                                                    "         <input id='" + birth_dateId + "' name='" + birth_dateId + "'\n" +
                                                    "           style=\"display: none\">\n" +
                                                    "                  </div>\n" +
                                                    "                     </div>\n" +

                                                        "   <select required id='"+start_address_id+"' name='"+start_address_id+"' class=\"form-control initial-value-example\">\n" +
                                                    "                                                            @if(!is_array($tour->gathering_place->address))\n" +
                                                    "                                                            <!-- for old version tours-->\n" +
                                                    "                                                                <option disabled readonly=\"true\" value=\"{{$tour->gathering_place->address}}\">  محل تجمع:&nbsp;{{$tour->gathering_place->address}}</option>\n" +
                                                    "                                                            @else\n" +
                                                    "<option selected=\"true\" value=\"\">محل تجمع (اجباری)</option>\n" +
                                                    "                                                                @foreach($tour->gathering_place->address as $gathering_address)\n" +

                                                    "                                                                    @if(count($tour->gathering_place->address) == 1)\n" +
                                                    "                                                                    <option value='{{$gathering_address -> address}}' selected>{{$gathering_address -> address}}</option>\n" +
                                                    "                                                                    @else\n" +
                                                    "                                                                    <option value='{{$gathering_address -> address}}'>{{$gathering_address -> address}}</option>\n" +
                                                    "                                                                    @endif\n" +

                                                    "                                                                @endforeach\n" +
                                                    "                                                            @endif\n" +
                                                    "                                                        </select>"+



                                                    "                     <div class=\"form-group\">\n" +
                                                    "                      <label for=\"email\">    {{trans('messages.lblEmail')}}\n" +
                                                    "  (اختیاری)</label>\n" +
                                                    "\n" +
                                                    " <div>\n" +
                                                    "   <input id='" + emailId + "' type=\"text\" name='" + email_name + "'\n" +
                                                    "   value=\"{{ old('email') }}\">\n" +
                                                    "    </div></div>" +

                                                    "<div class='form-group'>" +
                                                    "<label for='" + personal_description_name + "'>موارد خاص(اختیاری)</label>" +
                                                    "<div>" +
                                                    "<textarea id='" + personal_description_name + "' name='" + personal_description_name + "' placeholder=\"بیماری های خاص، دیابت، حساسیت، فشار خون و ...\"'>" +
                                                    "</textarea>" +
                                                    "</div>" +
                                                    "</div>" +

                                                    " <div class=\"form-group\" style=\"height: 55px;alignment: center\"  id='" + test + "'>"
                                                    + "<div class=\" \"  >" +
                                                    "" + "<button class=\" genric-btn danger-border \" id='" + btnId + "' style=\"margin-top: 10px;background-color: white;border: solid 1px grey\"  type=\"button\" title=\"حذف\"  onclick=\"Remove();\"> <i class=\"fa fa-remove\" style=\"font-size:40px;color:red\"></i>" +
                                                    "    </button></div" +
                                                    "</div>" +
                                                    "</div>" +
                                                    "</fieldset>";


                                                $('#entourage').append(Input);

                                                btnId = "btn_" + (count - 1);
                                                $("#" + btnId).remove();
                                                calender();
                                                // document.getElementById("final_cost").innerHTML = calculateCost();

                                            }

                                            function Remove() {
                                                comeradeCount--;
                                                person_count = person_count - 1;
                                                // if (document.getElementById("final_cost"))
                                                //     document.getElementById("final_cost").innerHTML = calculateCost();
                                                calculateCost();

                                                var count = $("#entourage > div").length;
                                                count++;//for main person
                                                count = count - 1;
                                                persId = "persInfo_" + count;
                                                $("#" + persId).remove();

                                                count = count - 1;
                                                persId = "persInfo_" + count;

                                                if (count != 0) {

                                                    var picRemoveButton =
                                                        "<div class=\"row\"  style=\"alignment: center;\"><div class=\"col-md-12\">" +
                                                        " <button class=\" genric-btn danger-border \" id='" + btnId + "' style=\"margin-top: 5px\" type=\"button\"  onclick=\"Remove();\"> <i class=\"fa fa-remove\" style=\"font-size:40px;color:red\"></i>" +
                                                        " </button></div> </div>";
                                                    test = "test_" + count;
                                                    // alert(test);
                                                    $("#" + test).append(picRemoveButton);

                                                }
                                            }

                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    {{--end of page section--}}

                    <hr>

                    <div class="row" id="footer">
                        <div class="col-md-12 text-center">
                            <p class="copyright-text">تمامی حقوق محفوظ است</p>
                        </div>

                    </div>


                    {{--**************************************************--}}
                </div>
            </div>
        </div>


    </form>

    <script>
        $(this).attr('data-id');

    </script>
@endsection


@section('page-js-script')
    <script type="text/javascript">

        var wholesaleDiscountIsActive = "{{$wholesaleDiscountIsActive}}";
        var wholesaleDiscountPercent = "{{$wholesaleDiscountPercent}}";

        var inNumericIncreased = true;
        var lastFeatureUid;
        var lastFeatureCost;

        var todayDateMiladi = "<?php echo Carbon::now()->format('Y-m-d'); ?>";
        var todayDateShamsi = "<?php echo $today; ?>";
        var todayDateShamsi2 = parseInt((todayDateShamsi.replace('-', '')).replace('-', ''));
        var minage = "<?php echo $tour->minimum_age; ?>";

        var maxage = "<?php echo $tour->maximum_age; ?>";


        var ph = [];
        var comeradeCount = 0;


        function changeFromatDate(date) {
            date = date.replace(/,/g, '-');
            return date.replace(/,/g, '-');
        }

        function containsObject(obj, list) {
            var i;
            for (i = 0; i < list.length; i++) {
                if (list[i].date === obj) {
                    return list[i];
                }
            }
            return false;
        }

        function isDatelast(date) {
            temp = date.split('-');
            date = temp[0] + (temp[1].length == 1 ? '0' + temp[1] : temp[1]) + (temp[2].length == 1 ? '0' + temp[2] : temp[2]);
            var date_num = parseInt(date);
            if (date_num < todayDateShamsi2) {
                return 1;
            } else if (date_num == todayDateShamsi2) {
                return 0;
            }
            return -1;
        }


        var classname = document.getElementsByClassName("pwt-btn");
        for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', ArrangeCalender, false);
        }

        ArrangeCalender();

        function ArrangeCalender() {
            var tds = $('#bigCalender .table-days td');

            for (var i = 0; i < tds.length; i++) {
                var temp0 = tds[i].getAttribute("data-date");
                temp1 = changeFromatDate(temp0);

                var t = isDatelast(temp1);
                if (t > 0) {
                    continue;
                } else if (t == 0) {
                    tds[i].innerHTML = tds[i].innerHTML + "<span style='color:#1f85d0' >امروز</span>";
                    continue;
                }

            }
        }

                {{--meysam--}}
        var originLatitude = "<?php

                if(is_array($tour->gathering_place->address))
                {
                    echo $tour->gathering_place->address[0]->latitude;
                }
                else
                {
                    echo $tour->gathering_place->latitude;
                }


                ?>";
        var originLongitude = "<?php

            if(is_array($tour->gathering_place->address))
            {
                echo $tour->gathering_place->address[0]->longitude;
            }
            else
            {
                echo $tour->gathering_place->longitude;
            }

            ?>";
        var address = "<?php

            if(is_array($tour->gathering_place->address))
            {
                $i=0;
                $count = count($tour->gathering_place->address);
                foreach ($tour->gathering_place->address as $address)
                {

                    if($count > 1 && $i != $count-1)
                    {
                        echo $address->address." - ";
                    }
                    else
                    {
                        echo $address->address;
                    }
                    $i++;

                }
            }
            else
            {
                echo $tour->gathering_place->address;
            }




            ?>";

        var goalLatitude = "<?php

            if(is_array($tour->tour_address->address))
            {
                echo $tour->tour_address->address[0]->latitude;
            }
            else
            {
                echo $tour->tour_address->latitude;
            }

            ?>";
        var goalLongitude = "<?php

            if(is_array($tour->tour_address->address))
            {
                echo $tour->tour_address->address[0]->longitude;
            }
            else
            {
                echo $tour->tour_address->longitude;
            }

            ?>";
        var title = "<?php

            if(is_array($tour->tour_address->address))
            {
                $i=0;
                $count = count($tour->tour_address->address);
                foreach ($tour->tour_address->address as $address)
                {
                     if($count > 1 && $i != $count-1)
                         {
                             echo $address->address." - ";
                         }
                        else
                        {
                            echo $address->address;
                        }
                    $i++;
                }



            }
            else
            {
                echo $tour->tour_address->address;
            }


            //echo $tour->tour_address->address;
            ?>";


        var chooseText = "<?php echo trans('messages.txtChoose') ?>";

        var activeStatus = '<?php echo \App\Tour::StatusActive ?>';

        $(document).ready(function () {
            var map;
            var originMap;

            if (!originLatitude) {
                originMap = L.map('originMap', {center: [29.617248, 52.543423]}).setView([29.617248, 52.543423], 16);

            }
            else {
                originMap = L.map('originMap').setView([originLatitude, originLongitude], 16);

            }

            // originMap.panTo(new L.LatLng(originLatitude, originLongitude));
            // map.fitBounds(markerBounds);

            if (!goalLatitude) {
                map = L.map('map').setView([29.617248, 52.543423], 16);

            }
            else {
                map = L.map('map').setView([goalLatitude, goalLongitude], 16);

            }


            L.tileLayer('https://developers.parsijoo.ir/web-service/v1/map/?type=tile&x={x}&y={y}&z={z}&apikey=4a910bd09861449b834625701bad4f2d',
                {
                    maxZoom: 19,
                }).addTo(map);

            L.tileLayer('https://developers.parsijoo.ir/web-service/v1/map/?type=tile&x={x}&y={y}&z={z}&apikey=4a910bd09861449b834625701bad4f2d',
                {
                    maxZoom: 19,
                }).addTo(originMap);


            if (originLatitude) {
                var popup = L.popup()
                    .setLatLng([originLatitude, originLongitude])
                    .setContent(address)
                    .openOn(originMap);

                var marker = L.marker([originLatitude, originLongitude], {
                    icon: L.icon({
                        iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                        // className: 'blinking'
                    })
                }).addTo(originMap);
            }


            if (goalLatitude) {
                var popup = L.popup()
                    .setLatLng([goalLatitude, goalLongitude])
                    .setContent(title)
                    .openOn(map);

                var marker = L.marker([goalLatitude, goalLongitude], {
                    icon: L.icon({
                        iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                        // className: 'blinking'
                    })
                }).addTo(map);
            }

        })

        // function refreshCaptcha() {
        //
        //     // alert('ddd');
        //
        //     $.ajax({
        //         url: "/refreshCaptcha",
        //         type: 'get',
        //         dataType: 'html',
        //         success: function (json) {
        //             // alert(json);
        //             $('.refereshrecapcha').html(json);
        //         },
        //         error: function (data) {
        //             // alert('Try Again.');
        //             swal({
        //                 position: 'center',
        //                 type: 'error',
        //             })
        //         }
        //     });
        // }

        calender();

        function calender() {
            var count = $("#entourage > div").length;
            count++;//for main person
            count = count - 1;
            start_date_show = "start_date_show_" + count;
            birth_dateId = "birth_date_" + count;

            $("#" + start_date_show).persianDatepicker({
                // $('#start_date_show_0').persianDatepicker({
                initialValue: false,
                altField: "#" + birth_dateId,
                altFormat: 'L',
                viewMode: 'year',
                autoClose: true,
                format: 'DD MMMM YYYY',
                toolbox: {
                    calendarSwitch: {
                        enabled: false
                    }
                },
                onSelect: function () {
                    startDateSelected(this.altField.substr(12));
                }
            });

        }

        String.prototype.toEnglishDigits = function () {
            var id = {
                '۰': '0',
                '۱': '1',
                '۲': '2',
                '۳': '3',
                '۴': '4',
                '۵': '5',
                '۶': '6',
                '۷': '7',
                '۸': '8',
                '۹': '9'
            };
            return this.replace(/[^0-9.]/g, function (w) {
                return id[w] || w;
            });
        };


        function startDateSelected(count) {

            // var count = $("#entourage > div").length;
            // count++;//for main person
            // count = count - 1;
            start_date_show = "start_date_show_" + count;
            birth_dateId = "birth_date_" + count;

            // $("#"+start_date_show).persianDatepicker({

            var temp = $("#" + birth_dateId).val();
            var input_year = temp.substring(0, 4);
            var today_year = todayDateShamsi.substring(0, 4);
            var new_input_year = input_year.toEnglishDigits();
            var mines = Number(today_year - new_input_year);

            if (mines < Number(minage) || mines > Number(maxage)) {

                $("#" + start_date_show).val("");
                // alert("سن در محدوده مورد قبول نیست.");

            }

        }

        function getValueForSelector(nameOfweek) {
            var temp = -1;
            for (var i = 0; i < document.getElementById("start_week_day_id").options.length; i++) {
                if (document.getElementById("start_week_day_id").options[i].text == nameOfweek) {
                    return i;
                }
            }
            return temp;
        }

        function showDetail(feature_uid) {
            var popup = document.getElementById("myPopup_" + feature_uid);
            if (popup != null && !popup.classList.contains("show")) {
                popup.classList.toggle("show");
            }
        }

        function HideDetail(feature_uid) {
            var popup = document.getElementById("myPopup_" + feature_uid);
            if (popup != null && popup.classList.contains("show")) {
                popup.classList.remove("show");
            }
        }

        function showDetailOptional(feature_uid) {
            var popup = document.getElementById("myPopupOptional_" + feature_uid);
            if (popup != null && !popup.classList.contains("show")) {
                popup.classList.toggle("show");
            }
        }

        function HideDetailOptional(feature_uid) {
            var popup = document.getElementById("myPopupOptional_" + feature_uid);
            if (popup != null && popup.classList.contains("show")) {
                popup.classList.remove("show");
            }
        }



        function calculateCost() {
            var argumentedFeaturesCost = 0;
            var totalBuyables = 0;
            if (document.getElementById("buyableFeatrues"))
                totalBuyables = document.getElementById("buyableFeatrues").childElementCount;
            // alert(totalBuyables);
            for (var i = 0; i < totalBuyables; i++) {
                var featureCost = document.getElementById("buyable_cost_" + i).value;
                var featureUid = document.getElementById("buyable_" + i).value;

                var isChecked = document.getElementById("chk_" + featureUid).checked;
                if (isChecked) {
                    argumentedFeaturesCost += (parseInt(document.getElementById("quantity_" + featureUid).value) * parseInt(featureCost));
                }

            }

            var base_cost = "<?php echo($tour->cost) ?>";

            if (wholesaleDiscountIsActive == 1 && person_count > 1) {
                var mainPersonCost = base_cost;

                var reductionPersonCost = 0.01 * (person_count - 1) * wholesaleDiscountPercent * mainPersonCost;
                mainPersonCost -= reductionPersonCost;

                if (mainPersonCost < 0)
                    mainPersonCost = 0;
                total_cost = argumentedFeaturesCost + ((person_count - 1) * base_cost) + mainPersonCost;
            }
            else {
                total_cost = argumentedFeaturesCost + (person_count * base_cost);
            }


            if (document.getElementById("final_cost") && (total_cost > 0)) {
                document.getElementById("final_cost").innerHTML = total_cost + " تومان ";
                if (document.getElementById("free"))
                    document.getElementById("free").innerHTML = "";
            }
            else {
                if (document.getElementById("free"))
                    document.getElementById("free").innerHTML = "رایگان";
                if (document.getElementById("final_cost"))
                    document.getElementById("final_cost").innerHTML = "";
            }

            if (document.getElementById("final_cost_mobile") && (total_cost > 0)) {
                document.getElementById("final_cost_mobile").innerHTML = total_cost + " تومان ";
                if (document.getElementById("free"))
                    document.getElementById("free").innerHTML = "";
            }
            else {
                if (document.getElementById("free"))
                    document.getElementById("free").innerHTML = "رایگان";
                document.getElementById("final_cost_mobile").innerHTML = "";
            }




            if (total_cost > 0)
                return (total_cost);
            else
                return "";
        }

        if (document.getElementById("final_cost"))
            calculateCost();
        if (document.getElementById("final_cost_mobile"))
            calculateCost();



        function validateMyForm() {
            // The field is empty, submit the form.
            if (!document.getElementById("phone").value) {
                return true;
            }
            // the field has a value it's a spam bot
            else {
                return false;
            }
        }

        function checkStartPlace() {

            var e = document.getElementById("start_address_0");
            var strValue = e.options[e.selectedIndex].value;
            if (strValue) {
                //do something
                // alert("wow");
            }
            else
            {
                // alert("oops");
                swal({
                    type: 'error',
                    title: 'خطا',
                    text: 'لطفا محل تجمع را مشخص نمایید '
                })
                event.preventDefault();
            }

            for (i = 0; i < comeradeCount; i++)
            {
                var e = document.getElementById("start_address_"+comeradeCount);
                var strValue = e.options[e.selectedIndex].value;
                if (strValue) {
                    //do something
                    // alert("wow");
                }
                else
                {
                    swal({
                        type: 'error',
                        title: 'خطا',
                        text: ' محل تجمع همراه شماره ' + comeradeCount + ' را مشخص کنید '
                    })
                    // alert("oops" + "start_address_"+comeradeCount);
                    event.preventDefault();
                }
            }



        }

    </script>
@stop