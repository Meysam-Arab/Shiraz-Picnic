@extends('layouts.new1master_tour')
@section('title',  trans('messages.tltTourDetails'))

@section('content')
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
    $temp_start_date = \Carbon\Carbon::parse($tour->miladi_start_date_time);
    $temp_start_date = \Carbon\Carbon::parse($temp_start_date->toDateString());
    if($temp_start_date->lt(\Carbon\Carbon::now()))
        $is_ended = true;

//    Log::info('ss:'.json_encode(Carbon::now()->toDateString()));
//    Log::info('ss:'.json_encode($tour->miladi_start_date_time));



    ?>
    <style>
        .fa-remove {
            display: block;
            margin: 0px auto;
        }

        div.fotorama, .fotorama div {
            direction: ltr;
        }

        .row {
            margin-right: 0px !important;
            margin-left: 0px !important;
        }

        select {
            margin-bottom: 1em;
            padding: .25em;
            border: 0;
            border-bottom: 2px solid currentcolor;
            font-weight: bold;
            letter-spacing: .15em;
            border-radius: 0;
        }

        #featuresListIcons .col-sm-6 {
            margin-bottom: 5px;
            /*float: right;*/
        }
    </style>
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
    <style>
        .error-notice {
            margin: 5px 5px; /* Making sure to keep some distance from all side */
        }

        .oaerror {
            width: 90%; /* Configure it fit in your design  */
            margin: 0 auto; /* Centering Stuff */
            background-color: #FFFFFF; /* Default background */
            padding: 20px;
            border: 1px solid #eee;
            border-right-width: 5px;
            border-radius: 3px;
            margin: 0 auto;
            /*font-family: 'Open Sans', sans-serif;*/
            /*font-size: 16px;*/
        }

        .danger {
            border-color: white;
            border-right-color: #d9534f; /* Left side border color */
            background-color: rgba(192, 192, 192, 0.3); /* Same color as the left border with reduced alpha to 0.1 */

        }

        .danger strong {
            color: #d9534f;
        }

        .warning {
            border-color: white;
            border-right-color: #f0ad4e;
            background-color: rgba(192, 192, 192, 0.3);
        }

        .warning strong {
            color: #f0ad4e;
        }

        .info {
            border-color: white;
            border-right-color: #5bc0de;
            background-color: rgba(192, 192, 192, 0.3);
        }

        .info strong {
            color: #5bc0de;
        }

        .success {
            border-color: white;
            border-right-color: #2b542c;
            background-color: rgba(192, 192, 192, 0.3);
        }

        .success strong {
            color: #2b542c;
        }

        .fotorama__wrap {
            margin-left: auto;
            margin-right: auto;
        }

        #featuresListIcons1 .col-sm-4 {
            margin-bottom: 5px;
        }
    </style>
    <style>

        .circle {
            /*height: 100px;*/
            /*width: 100px;*/
            border-radius: 50%;
            border:1px solid #3a87ad;
            line-height: 50px;
            text-align: center;
            font-size: 15px;
        }
    </style>
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

    <div class="banner-bg" id="top">
        <div class="banner-overlay"><img
                    src="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/1/3.22')}}" alt=""
                    style="object-fit:cover;width: 100%;height: 100%"></div>
        <div class="welcome-text">
            <h2>{!! $tour->title !!}</h2>
            <h5>{!! $tour->tour_address->address !!}</h5>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="fluid-container">

            <div class="content-wrapper">
                <!-- Address -->
                <div class="page-section" id="address">
                    <div class="row">
                        <div class="col-md-12" dir="rtl">
                            <h4 class="widget-title" style="color: black">آدرس</h4>
                            <div class="col-md-6">
                                <label>مقصد: {!! $tour->tour_address->address !!} </label>
                                <div class="about-image">
                                    <div id="map"
                                         style="width:auto;height:262px;display: block;margin-right: auto;margin-left: auto">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <label> مبدا: {{$tour->gathering_place->address}} </label>
                                <div class="about-image">
                                    <div id="originMap"
                                         style="width:auto;height:262px;display: block;margin-right: auto;margin-left: auto">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--  End Of Address -->
                <hr>
                <!-- ABOUT -->
                <div class="page-section" id="about">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="widget-title" style="color: black;margin-top: 5px">گالری تصاویر</h4>
                            <div class="fotorama about-image" data-allowfullscreen="native" data-nav="thumbs">
                                @foreach($pictures as $picture)
                                    {{--<img src="{{URL::to('gardens/getFile/'.$garden->garden_id.'/'.$garden->garden_guid.'/'.$picture->media_id.'/2.22')}}">--}}
                                    {{--<a href="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/'.$picture->media_id.'/3.22')}}">--}}
                                    <img
                                            src="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/'.$picture->media_id.'/3.22')}}">
                                    {{--</a>--}}

                                @endforeach
                            </div>
                            <hr>
                            {{--Leaders--}}
                            <div class="row" style="margin-top: 10px">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <div class="row">
                                        @foreach($leaders as $leader)
                                            <div class=" col-md-6">
                                                <div style="position: relative;">

                                                    <img src="{{URL::to('users/getFile/'.$leader->user_id.'/'.$leader->user_guid.'/1.11')}}"
                                                         alt=""
                                                         style="display: block;width: 150px;height: 150px;background-size: cover;margin-right: auto;margin-left: auto;
                                                     background-repeat: no-repeat;background-position: center center;-webkit-border-radius: 99em;
                                                     -moz-border-radius: 99em;border-radius: 99em;border: 5px solid #eee; box-shadow: 0 3px 2px rgba(0, 0, 0, 0.3);">
                                                </div>
                                                <div class="row" style="text-align: center">

                                                    {{$leader->name_family}}

                                                </div>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                            {{--end of row for leader--}}
                        </div>
                    </div>
                </div>
                <!-- End Of About -->
                <div class="page-section" id="Conditions">
                    <h4 class="widget-title" style="color: black;margin-top: 5px">شرایط و قوانین</h4>
                    {{--<div class="row">--}}
                    {{--<div class="col-md-12">--}}
                    {{--<h4 class="widget-title" style="color:black;">  </h4>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <br>
                    <style>
                        .table {
                            text-align: center;
                            border: 3px solid #b6b6b6;
                        }

                        .table img {
                            vertical-align: middle;
                            margin: 0px auto;
                            display: block;
                        }

                        .table h3 {
                            text-align: center;
                        }

                        .table01 .table th, .table01 .table td {
                            border: 0px;
                            text-align: center;
                        }

                        .table01 hr {
                            margin-top: 10px;
                        }
                    </style>
                    <div class="row table01">
                        <h2 style="text-align:center;margin-bottom: 5px;font-size: larger">جدول اطلاعات</h2>
                        {{--<p>The .table-bordered class adds border on all sides of the table and cells:</p>--}}
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    <img src="{{url('/SVGs_Tour/clock.svg')}}" width="30px" alt=""
                                         title=" تاریخ و ساعت حرکت"><br>
                                    <h3>حرکت</h3>

                                    <hr>
                                    <?php
                                    $ip = $tour->start_date_time; // some IP address
                                    $iparr = explode (" ", $ip);
                                    echo ($iparr[0]);

                                    ?>
                                    <br>
                                    <?php
                                    $ip = $tour->start_date_time; // some IP address
                                    $iparr = explode (" ", $ip);
                                    echo ($iparr[1]);

                                    ?>

                                </th>
                                <th style="border-left: 2px solid #b6b6b6;">
                                    <img src="{{url('/SVGs_Tour/clock.svg')}}" width="30px" alt=""
                                         title=" تاریخ و ساعت بازگشت"><br>
                                    <h3>بازگشت</h3>
                                    <hr>

                                    <?php
                                    $ip = $tour->end_date_time; // some IP address
                                    $iparr = explode (" ", $ip);
                                    echo ($iparr[0]);

                                    ?>
                                    <br>
                                    <?php
                                    $ip = $tour->end_date_time; // some IP address
                                    $iparr = explode (" ", $ip);
                                    echo ($iparr[1]);

                                    ?>

                                </th>
                                <th>
                                    <img src="{{url('/SVGs_Tour/icon.svg')}}" width="30px" alt=""
                                         title="جنسیت"><br>
                                    <h3>جنسیت</h3>
                                    <hr>
                                    {{ \App\Tour::getGenderStringByCode($tour->gender)}}

                                </th>
                                <th>
                                    <img src="{{url('/SVGs_Tour/baby.svg')}}" width="30px" alt=""
                                         title="حداقل سن"><br>
                                    <h3>حداقل سن</h3>
                                    <hr>
                                    {{$tour->minimum_age}}
                                </th>
                                <th>
                                    <img src="{{url('/SVGs_Tour/man.svg')}}" width="30px" alt=""
                                         title="حداکثر سن"><br>
                                    <h3>حداکثر سن</h3>
                                    <hr>
                                    {{$tour->maximum_age}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr style="border-top: 3px solid #b6b6b6;">
                                <td colspan="2" style="border-left: 3px solid #b6b6b6;text-align: right;">
                                    <br>
                                    <span style="color: black;padding-right: 20px;">شرکت:</span> {{$info-> company}}
                                    <br><br>
                                    <span style="color: black;padding-right: 20px">شماره تماس:</span> {{$info->coordination_tel}}
                                    <br><br>
                                    <div style="background-color: #3a87ad;color: white;padding: 5px;text-align: center">
                                        قیمت:
                                        @if($tour->cost == 0)
                                            رایگان
                                        @else
                                            {{number_format($tour->cost)}} تومان
                                        @endif

                                    </div>


                                </td>
                                <td colspan="3">
                                    <br>
                                    <div class="row" style="">
                                        <div class="col-md-4">
                                            <h3> ظرفیت باقی مانده:</h3><br>
                                            <div class="col-md-12"> <div class="circle" id="remaincapacity"><span>{{$tour->remaining_capacity}}</span></div></div>
                                                <script>
                                                    var remain = "<?php echo $tour->remaining_capacity ?>";
                                                    if( remain <10){
                                                        document.getElementById("remaincapacity").style.backgroundColor = "red";
                                                    }

                                                </script>
                                        </div>
                                        <div class="col-md-4">
                                            <h3> ظرفیت کل:</h3><br>
                                            <div class="col-md-12"><div class="circle" style=" "><span>{{$tour->total_capacity}}</span></div></div>
                                        </div>

                                        <div class="col-md-4">
                                             <h3>درجه سختی:</h3><br>
                                            <div class="col-md-12">   <div class="circle"><span>{{$tour->hardship_level}}</span></div></div>

                                        </div>

                                    </div>

                                </td>
                            </tr>
                            <tr style="border-top: 3px solid #b6b6b6">
                                <td colspan="5" style="text-align: right">
                                    <span style="padding-right: 20px;color: black"> توضیحات: </span>{{$tour->description}}
                                </td>
                            </tr>
                            <tr style="border-top: 3px solid #b6b6b6">
                                <td colspan="5" style="text-align: right">
                                    <span style="padding-right: 20px;color:black;">قوانین: </span>{{$info-> regulations}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <hr>

                {{--//////// start force--}}

                <div class="row" dir="rtl">
                    <h4 class="widget-title" style="margin-top: 5px;color: black"> {{trans('messages.lblForce')}}</h4>
                </div>
                <br>



                @if(isset($features))
                    @if(count($features) > 0)
                        <div class="alert alert-warning">
                            برای همراهی با تور بایستی موارد زیر را همراه داشته باشید.
                        </div>

                        @foreach($features as $feature)
                            @if($feature->is_required == 1)

                                <div class="col-xs-6" style="margin-bottom: 10px">
                                <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}" width="30px" alt=""
                                style="vertical-align: middle;margin-left:0px;" title="{{$feature->name}}">
                                <span class="features">{{$feature->name}}</span>
                                @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                <div class="Popups" onmouseover="showDetail({{$feature->feature_uid}})" onmouseout="HideDetail({{$feature->feature_uid}})">
                                <sup>
                                <a class="fa fa-question-circle" aria-hidden="true"
                                style="color: #6b63e4"></a>
                                </sup>
                                <span class="Popuptext" id="myPopup_{{$feature->feature_uid}}">
                                <p>{!! $feature->description." ".$feature->more_description  !!}</p>
                                </span>
                                </div>
                                @else
                                <span id="myPopup_{{$feature->feature_uid}}"></span>
                                @endif
                                </div>




                            @endif
                        @endforeach


                        <div class="row ft01" dir="rtl">
                            <div class="col-md-12" id="featuresListIcons1">



                                {{--*****************************gps*******************************--}}
                                {{--@if( isset($required->gps) && $required->gps)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/gps.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" GPS">--}}
                                {{--<span class="features">{{trans('messages.lblGps')}}</span>--}}
                                {{--@if(strcmp($required->gps_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(1)" onmouseout="HideDetail2(1)">--}}
                                {{--<sup>--}}
                                {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup1">--}}
                                {{--<p>{!! $required->gps_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup1"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************casket*******************************--}}
                                {{--@if( isset($required->casket) && $required->casket)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/casket.svg')}}" width="30px" alt="کلاه ایمنی">--}}
                                {{--<span class="features">{{trans('messages.lblCasket')}}</span>--}}
                                {{--@if(strcmp($required->casket_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(2)" onmouseout="HideDetail2(2)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup2">--}}
                                {{--<p>{!! $required->casket_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup2"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************rope*******************************--}}
                                {{--@if( isset($required->rope) &&  $required->rope)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/rope.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" طناب">--}}
                                {{--<span class="features">{{trans('messages.lblRope')}}</span>--}}
                                {{--@if(strcmp($required->rope_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(3)" onmouseout="HideDetail2(3)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup3">--}}
                                {{--<p>{!! $required->rope_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup3"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************carabin*******************************--}}
                                {{--@if(  isset($required->carabin) && $required->carabin)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/carabiner.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" کارابین">--}}
                                {{--<span class="features">{{trans('messages.lblCarabin')}}</span>--}}
                                {{--@if(strcmp($required->carabin_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(4)" onmouseout="HideDetail2(4)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup4">--}}
                                {{--<p>{!! $required->carabin_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup4"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************eight*******************************--}}
                                {{--@if(  isset($required->eight) && $required->eight)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/eight.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" هشت فرود">--}}
                                {{--<span class="features">{{trans('messages.lblEight')}}</span>--}}
                                {{--@if(strcmp($required->eight_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(5)" onmouseout="HideDetail2(5)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup5">--}}
                                {{--<p>{!! $required->eight_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup5"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************harness*******************************--}}
                                {{--@if(  isset($required->harness) && $required->harness)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/harness.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" هارنس">--}}
                                {{--<span class="features">{{trans('messages.lblHarness')}}</span>--}}
                                {{--@if(strcmp($required->harness_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(6)" onmouseout="HideDetail2(6)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup6">--}}
                                {{--<p>{!! $required->harness_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup6"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************wet_suit*******************************--}}
                                {{--@if(  isset($required->wet_suit) && $required->wet_suit)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/wetsuit.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" لباس شنا">--}}
                                {{--<span class="features">{{trans('messages.lblWet_suit')}}</span>--}}
                                {{--@if(strcmp($required->wet_suit_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(7)" onmouseout="HideDetail2(7)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup7">--}}
                                {{--<p>{!! $required->wet_suit_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup7"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************rescue_vest*******************************--}}
                                {{--@if(  isset($required->rescue_vest) && $required->rescue_vest)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/rescue_vest.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" جلیقه نجات">--}}
                                {{--<span class="features">{{trans('messages.lblRescue_vest')}}</span>--}}
                                {{--@if(strcmp($required->rescue_vest_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(8)" onmouseout="HideDetail2(8)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup8">--}}
                                {{--<p>{!! $required->rescue_vest_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup8"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************Breakfast*******************************--}}
                                {{--@if(  isset($required->breakfast) && $required->breakfast)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/breakfast.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" صبحانه">--}}
                                {{--<span class="features">{{trans('messages.lblBreakfast')}}</span>--}}
                                {{--@if(strcmp($required->breakfast_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(9)" onmouseout="HideDetail2(9)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup9">--}}
                                {{--<p>{!! $required->breakfast_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup9"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************lunch*******************************--}}
                                {{--@if(  isset($required->lunch) && $required->lunch)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/lunch.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" نهار">--}}
                                {{--<span class="features">{{trans('messages.lblLunch')}}</span>--}}
                                {{--@if(strcmp($required->lunch_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(10)" onmouseout="HideDetail2(10)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup10">--}}
                                {{--<p>{!! $required->lunch_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup10"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************dinner*******************************--}}
                                {{--@if(  isset($required->dinner) && $required->dinner)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/dinner.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" شام">--}}
                                {{--<span class="features">{{trans('messages.lblDinner')}}</span>--}}
                                {{--@if(strcmp($required->dinner_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(11)" onmouseout="HideDetail2(11)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup11">--}}
                                {{--<p>{!! $required->dinner_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup11"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************evening_meal*******************************--}}
                                {{--@if(  isset($required->evening_meal) && $required->evening_meal)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/evening_meal.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" عصرانه">--}}
                                {{--<span class="features">{{trans('messages.lblEvening_meal')}}</span>--}}
                                {{--@if(strcmp($required->evening_meal_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(12)" onmouseout="HideDetail2(12)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup12">--}}
                                {{--<p>{!! $required->evening_meal_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup12"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}

                                {{--*****************************plastic*******************************--}}
                                {{--@if(  isset($required->plastic) && $required->plastic)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/plastic.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" پلاستیک">--}}
                                {{--<span class="features">{{trans('messages.lblPlastic')}}</span>--}}
                                {{--@if(strcmp($required->plastic_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(13)" onmouseout="HideDetail2(13)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup13">--}}
                                {{--<p>{!! $required->plastic_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup13"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************ground_cloth*******************************--}}
                                {{--@if(  isset($required->ground_cloth) && $required->ground_cloth)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/mat.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" زیر انداز">--}}
                                {{--<span class="features">{{trans('messages.lblGround_cloth')}}</span>--}}
                                {{--@if(strcmp($required->ground_cloth_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(14)" onmouseout="HideDetail2(14)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup14">--}}
                                {{--<p>{!! $required->ground_cloth_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup13"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************tent*******************************--}}
                                {{--@if(  isset($required->tent) && $required->tent)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/tent1.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" چادر">--}}
                                {{--<span class="features">{{trans('messages.lblTent')}}</span>--}}
                                {{--@if(strcmp($required->tent_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(15)" onmouseout="HideDetail2(15)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup15">--}}
                                {{--<p>{!! $required->tent_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup15"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************sleeping_bag*******************************--}}
                                {{--@if(  isset($required->sleeping_bag) && $required->sleeping_bag)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/sleeping-bag.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" کیسه خواب">--}}
                                {{--<span class="features">{{trans('messages.lblSleeping_bag')}}</span>--}}
                                {{--@if(strcmp($required->sleeping_bag_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(16)" onmouseout="HideDetail2(16)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup16">--}}
                                {{--<p>{!! $required->sleeping_bag_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup16"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************suitable_shoes*******************************--}}
                                {{--@if(  isset($required->suitable_shoes) && $required->suitable_shoes)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/special_shoes.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title="  کفش مخصوص ">--}}
                                {{--<span class="features">{{trans('messages.lblSpecial_shoes')}}</span>--}}
                                {{--@if(strcmp($required->suitable_shoes_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(17)" onmouseout="HideDetail2(17)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup17">--}}
                                {{--<p>{!! $required->suitable_shoes_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup17"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************entrance_fee*******************************--}}
                                {{--@if(  isset($required->entrance_fee) && $required->entrance_fee)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/money.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" هزینه ورودی">--}}
                                {{--<span class="features">{{trans('messages.lblEntrance_fee')}}</span>--}}
                                {{--@if(strcmp($required->entrance_fee_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(18)" onmouseout="HideDetail2(18)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup18">--}}
                                {{--<p>{!! $required->entrance_fee_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup18"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************head_lump*******************************--}}
                                {{--@if(  isset($required->head_lump) && $required->head_lump)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/headlamp.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" هد لامپ">--}}
                                {{--<span class="features">{{trans('messages.lblHead_lump')}}</span>--}}
                                {{--@if(strcmp($required->head_lump_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(19)" onmouseout="HideDetail2(19)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup19">--}}
                                {{--<p>{!! $required->head_lump_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup19"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************gloves*******************************--}}
                                {{--@if(  isset($required->gloves) && $required->gloves)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/gloves.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" دستکش">--}}
                                {{--<span class="features">{{trans('messages.lblGloves')}}</span>--}}
                                {{--@if(strcmp($required->gloves_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(20)" onmouseout="HideDetail2(20)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup20">--}}
                                {{--<p>{!! $required->gloves_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup20"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************transfer*******************************--}}
                                {{--@if(  isset($required->transfer) && $required->transfer)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/bus.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" وسیله حمل و نقل  ">--}}
                                {{--<span class="features">{{trans('messages.lblTransfer')}}</span>--}}
                                {{--@if(strcmp($required->transfer_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(21)" onmouseout="HideDetail2(21)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup21">--}}
                                {{--<p>{!! $required->transfer_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup21"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************diving_suits*******************************--}}
                                {{--@if(  isset($required->diving_suits) && $required->diving_suits)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/diving-suit.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title="  لباس غواصی">--}}
                                {{--<span class="features">{{trans('messages.lblDiving_suits')}}</span>--}}
                                {{--@if(strcmp($required->diving_suits_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(22)" onmouseout="HideDetail2(22)">--}}
                                {{--<sup>--}}
                                {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup22">--}}
                                {{--<p>{!! $required->diving_suits_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup22"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************extra_clothes*******************************--}}
                                {{--@if(  isset($required->extra_clothes) && $required->extra_clothes)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/extra_clothes.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" لباس اضافه">--}}
                                {{--<span class="features">{{trans('messages.lblٍxtra_clothes')}}</span>--}}
                                {{--@if(strcmp($required->extra_clothes_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(23)" onmouseout="HideDetail2(23)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup23">--}}
                                {{--<p>{!! $required->extra_clothes_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup23"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}

                                {{--*****************************battery*******************************--}}
                                {{--@if(  isset($required->battery) && $required->battery)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/battery.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" باتری">--}}
                                {{--<span class="features">{{trans('messages.lblٍBattery')}}</span>--}}
                                {{--@if(strcmp($required->battery_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(24)" onmouseout="HideDetail2(24)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup24">--}}
                                {{--<p>{!! $required->battery_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup24"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}
                                {{--*****************************other*******************************--}}
                                {{--@if(  isset($required->other) && $required->other)--}}
                                {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                {{--<img src="{{url('/SVGs_Tour/other.svg')}}" width="30px" alt=""--}}
                                {{--style="vertical-align: middle;margin-left:0px;" title=" سایر">--}}
                                {{--<span class="features">{{trans('messages.lblOther')}}</span>--}}
                                {{--@if(strcmp($required->other_description,'ندارد')!=0)--}}
                                {{--<div class="Popups" onmouseover="showDetail2(25)" onmouseout="HideDetail2(25)">--}}
                                {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                {{--style="color: #6b63e4"></a>--}}
                                {{--</sup>--}}
                                {{--<span class="Popuptext" id="MyPopup25">--}}
                                {{--<p>{!! $required->other_description !!}</p>--}}
                                {{--</span>--}}
                                {{--</div>--}}
                                {{--@else--}}
                                {{--<span id="MyPopup25"></span>--}}
                                {{--@endif--}}
                                {{--</div>--}}
                                {{--@endif--}}

                            </div>
                        </div>

                        <hr>
                    @endif
                @endif


                <div class="page-section" id="projects" dir="rtl">
                    <div class="row" dir="rtl">
                        <div class="col-md-12" dir="rtl">
                            <h4 class="widget-title"
                                style="color:black;margin-top: 5px">  {{trans('messages.lblService_of_tour')}}</h4>
                            <br>
                        </div>
                    </div>



                    @if(isset($features))
                        @if(count($features) > 0)

                            <div class="row" dir="rtl">
                                <div class="alert alert-success">
                                    موارد زیر برای شما فراهم خواهد بود.
                                </div>


                                @foreach($features as $feature)
                                    @if(($feature->is_required == 0) && ($feature->is_optional == 0))

                                        <div class="col-xs-6" style="margin-bottom: 10px">
                                        <div class="col-md-2" style="float: right">
                                        <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}" width="30px" alt=""
                                        style="vertical-align: middle;margin-left:0px;" title="{{$feature->name}}">
                                        <span class="features">{{$feature->name}}</span>
                                        @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                        <div class="popups" onmouseover="showDetail({{$feature->feature_uid}})" onmouseout="HideDetail({{$feature->feature_uid}})">
                                        <sup> <a class="fa fa-question-circle" aria-hidden="true"
                                        style="color: #6b63e4"></a>
                                        </sup>
                                        <span class="popuptext" id="myPopup_{{$feature->feature_uid}}">
                                        <p>{!! $feature->description." ".$feature->more_description !!}</p>
                                        </span>
                                        </div>
                                        @else
                                        <span id="myPopup_{{$feature->feature_uid}}"></span>
                                        @endif
                                        </div>




                                    @endif
                                @endforeach

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
                                <div class="row" dir="rtl">


                                    {{--*****************************gps*******************************--}}
                                    {{--@if(  isset($features->gps) && $features->gps && !($features->gps_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<div class="col-md-2" style="float: right">--}}
                                    {{--<img src="{{url('/SVGs_Tour/gps.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" GPS">--}}
                                    {{--<span class="features">{{trans('messages.lblGps')}}</span>--}}
                                    {{--@if(strcmp($features->gps_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(1)" onmouseout="HideDetail(1)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup1">--}}
                                    {{--<p>{!! $features->gps_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup1"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--******************************casket*******************************--}}
                                    {{--@if(  isset($features->casket) && $features->casket && !($features->casket_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/helmet.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title="کلاه ایمنی">--}}
                                    {{--<span class="features">{{trans('messages.lblCasket')}}</span>--}}
                                    {{--@if(strcmp($features->casket_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(2)" onmouseout="HideDetail(2)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup2">--}}
                                    {{--<p>{!! $features->casket_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup2"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--************************************rope*******************************************--}}
                                    {{--@if(  isset($features->rope) && $features->rope && !($features->rope_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/rope.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title="طناب ">--}}
                                    {{--<span class="features">{{trans('messages.lblRope')}}</span>--}}
                                    {{--@if(strcmp($features->rope_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(3)" onmouseout="HideDetail(3)">--}}
                                    {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup3">--}}
                                    {{--<p>{!! $features->rope_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup3"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}

                                    {{--@endif--}}

                                    {{--*******************************carabin************************************************--}}
                                    {{--@if(  isset($features->carabin) && $features->carabin && !($features->carabin_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/carabiner.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" کارابین">--}}
                                    {{--<span class="features">{{trans('messages.lblCarabin')}}</span>--}}
                                    {{--@if(strcmp($features->carabin_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(4)" onmouseout="HideDetail(4)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup4">--}}
                                    {{--<p>{!! $features->carabin_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup4"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--*******************************eight************************************************--}}
                                    {{--@if(  isset($features->eight) && $features->eight && !($features->eight_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/eight.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" هشت فرود">--}}
                                    {{--<span class="features">{{trans('messages.lblEight')}}</span>--}}
                                    {{--@if(strcmp($features->eight_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(5)" onmouseout="HideDetail(5)">--}}
                                    {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup5">--}}
                                    {{--<p>{!! $features->eight_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup5"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--*****************************harness**************************************************--}}
                                    {{--@if(  isset($features->harness) && $features->harness && !($features->harness_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/harness.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" هارنس">--}}
                                    {{--<span class="features">{{trans('messages.lblHarness')}}</span>--}}
                                    {{--@if(strcmp($features->harness_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(6)" onmouseout="HideDetail(6)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup6">--}}
                                    {{--<p>{!! $features->harness_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup6"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************wet_suit*************************************************--}}
                                    {{--@if(  isset($features->wet_suit) && $features->wet_suit && !($features->wet_suit_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/wetsuit.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" لباس شنا">--}}
                                    {{--<span class="features">{{trans('messages.lblWet_suit')}}</span>--}}
                                    {{--@if(strcmp($features->wet_suit_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(7)" onmouseout="HideDetail(7)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup7">--}}
                                    {{--<p>{!! $features->wet_suit_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup7"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--******************************rescue_vest*************************************************--}}
                                    {{--@if(  isset($features->rescue_vest) && $features->rescue_vest && !($features->rescue_vest_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/rescue_vest.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" جلیقه نجات">--}}
                                    {{--<span class="features">{{trans('messages.lblRescue_vest')}}</span>--}}
                                    {{--@if(strcmp($features->rescue_vest_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(8)" onmouseout="HideDetail(8)">--}}
                                    {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a></sup>--}}
                                    {{--<span class="popuptext" id="myPopup8">--}}
                                    {{--<p>{!! $features->rescue_vest_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup8"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************breakfast*************************************************--}}
                                    {{--@if(  isset($features->breakfast) && $features->breakfast && !($features->breakfast_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/breakfast.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" صبحانه">--}}
                                    {{--<span class="features">{{trans('messages.lblBreakfast')}}</span>--}}
                                    {{--@if(strcmp($features->breakfast_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(9)" onmouseout="HideDetail(9)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup9">--}}
                                    {{--<p>{!! $features->breakfast_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup9"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************lunch*************************************************--}}
                                    {{--@if(  isset($features->lunch) && $features->lunch && !($features->lunch_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/lunch.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" نهار">--}}
                                    {{--<span class="features">{{trans('messages.lblLunch')}}</span>--}}
                                    {{--@if(strcmp($features->lunch_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(10)"--}}
                                    {{--onmouseout="HideDetail(10)">--}}
                                    {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup10">--}}
                                    {{--<p>{!! $features->lunch_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup10"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--******************************dinner*************************************************--}}
                                    {{--@if(  isset($features->dinner) && $features->dinner && ! ($features->dinner_optional) )--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/dinner.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" شام">--}}
                                    {{--<span class="features">{{trans('messages.lblDinner')}}</span>--}}
                                    {{--@if(strcmp($features->dinner_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(11)"--}}
                                    {{--onmouseout="HideDetail(11)">--}}
                                    {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup11">--}}
                                    {{--<p>{!! $features->dinner_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup11"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}

                                    {{--@endif--}}

                                    {{--******************************evening_meal*************************************************--}}
                                    {{--@if(  isset($features->evening_meal) && $features->evening_meal && ! ($features->evening_meal_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/evening_meal.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" عصرانه">--}}
                                    {{--<span class="features">{{trans('messages.lblEvening_meal')}}</span>--}}
                                    {{--@if(strcmp($features->evening_meal_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(12)"--}}
                                    {{--onmouseout="HideDetail(12)">--}}
                                    {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup12">--}}
                                    {{--<p>{!! $features->evening_meal_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup12"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}

                                    {{--@endif--}}

                                    {{--******************************catering_on_the_route*************************************************--}}
                                    {{--@if(   isset($features->catering_on_the_route) && $features->catering_on_the_route && !($features->catering_on_the_route_optional) )--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/catering_on_the_route.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" پذیرایی در مسیر">--}}
                                    {{--<span class="features">{{trans('messages.lblCatering_on_the_route')}}</span>--}}
                                    {{--@if(strcmp($features->catering_on_the_route_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(13)"--}}
                                    {{--onmouseout="HideDetail(13)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup13">--}}
                                    {{--<p>{!! $features->catering_on_the_route_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup13"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}

                                    {{--@endif--}}
                                    {{--******************************parking*************************************************--}}
                                    {{--@if(   isset($features->parking) && $features->parking && ! ($features->parking_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/parking-sign.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" پارکینگ">--}}
                                    {{--<span class="features">{{trans('messages.lblParking')}}</span>--}}
                                    {{--@if(strcmp($features->parking_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(14)"--}}
                                    {{--onmouseout="HideDetail(14)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup14">--}}
                                    {{--<p>{!! $features->parking_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup14"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--******************************local_guide*************************************************--}}
                                    {{--@if(   isset($features->local_guide) && $features->local_guide && ! ($features->local_guide_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/local_guide.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" راهنمای محلی">--}}
                                    {{--<span class="features">{{trans('messages.lblLocal_guide')}}</span>--}}
                                    {{--@if(strcmp($features->local_guide_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(15)"--}}
                                    {{--onmouseout="HideDetail(15)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup15">--}}
                                    {{--<p>{!! $features->local_guide_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup15"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************plastic*************************************************--}}
                                    {{--@if(   isset($features->plastic) && $features->plastic && ! ($features->plastic_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/plastic.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" پلاستیک">--}}
                                    {{--<span class="features">{{trans('messages.lblPlastic')}}</span>--}}
                                    {{--@if(strcmp($features->plastic_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(16)"--}}
                                    {{--onmouseout="HideDetail(16)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup16">--}}
                                    {{--<p>{!! $features->plastic_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup16"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--************************************photographer*******************************************--}}
                                    {{--@if(   isset($features->photographer) && $features->photographer && ! ($features->photographer_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/photographer.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" عکاس">--}}
                                    {{--<span class="features">{{trans('messages.lblPhotographer')}}</span>--}}
                                    {{--@if(strcmp($features->photographer_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(17)"--}}
                                    {{--onmouseout="HideDetail(17)">--}}
                                    {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup17">--}}
                                    {{--<p>{!! $features->photographer_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup17"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************ground_cloth*************************************************--}}
                                    {{--@if(   isset($features->ground_cloth) && $features->ground_cloth && ! ($features->ground_cloth_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/ground_cloth.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" زیر انداز">--}}
                                    {{--<span class="features">{{trans('messages.lblGround_cloth')}}</span>--}}
                                    {{--@if(strcmp($features->ground_cloth_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(18)"--}}
                                    {{--onmouseout="HideDetail(18)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup18">--}}
                                    {{--<p>{!! $features->ground_cloth_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup18"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}

                                    {{--@endif--}}

                                    {{--******************************music*************************************************--}}
                                    {{--@if(   isset($features->music) && $features->music && ! ($features->music_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/musica.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" موسیقی">--}}
                                    {{--<span class="features">{{trans('messages.lblMusic')}}</span>--}}
                                    {{--@if(strcmp($features->music_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(19)"--}}
                                    {{--onmouseout="HideDetail(19)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup19">--}}
                                    {{--<p>{!! $features->music_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup19"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}

                                    {{--@endif--}}
                                    {{--******************************tent*************************************************--}}
                                    {{--@if(   isset($features->tent) && $features->tent && !($features->tent_optional) )--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/tent1.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" چادر">--}}
                                    {{--<span class="features">{{trans('messages.lblTent')}}</span>--}}
                                    {{--@if(strcmp($features->tent_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(20)"--}}
                                    {{--onmouseout="HideDetail(20)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup20">--}}
                                    {{--<p>{!! $features->tent_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup20"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}

                                    {{--@endif--}}

                                    {{--******************************professional_guide*************************************************--}}
                                    {{--@if(   isset($features->professional_guide) && $features->professional_guide && !($features->professional_guide_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/professional_guide.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" راهنمای حرفه ایی">--}}
                                    {{--<span class="features">{{trans('messages.lblProfessional_guide')}}</span>--}}
                                    {{--@if(strcmp($features->professional_guide_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(21)"--}}
                                    {{--onmouseout="HideDetail(21)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup21">--}}
                                    {{--<p>{!! $features->professional_guide_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup21"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}


                                    {{--******************************sleeping_bag*************************************************--}}
                                    {{--<sup class="star">*</sup>--}}
                                    {{--@if(   isset($features->sleeping_bag) && $features->sleeping_bag && !($features->sleeping_bag_optional) )--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/sleeping-bag.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" کیسه خواب">--}}
                                    {{--<span class="features">{{trans('messages.lblSleeping_bag')}}</span>--}}
                                    {{--@if(strcmp($features->sleeping_bag_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(22)"--}}
                                    {{--onmouseout="HideDetail(22)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}

                                    {{--</sup>--}}

                                    {{--<span class="popuptext" id="myPopup22">--}}
                                    {{--<p>{!! $features->sleeping_bag_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup22"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--******************************insurance*************************************************--}}
                                    {{--@if(   isset($features->insurance) && $features->insurance && ! ($features->insurance_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/insurance.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" بیمه">--}}
                                    {{--<span class="features">{{trans('messages.lblInsurance')}}</span>--}}
                                    {{--@if(strcmp($features->insurance_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(23)"--}}
                                    {{--onmouseout="HideDetail(23)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup23">--}}
                                    {{--<p>{!! $features->insurance_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup23"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--******************************special_shoes*************************************************--}}

                                    {{--@if(   isset($features->special_shoes) && $features->special_shoes && ! ($features->special_shoes_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/special_shoes.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" کفش مخصوص">--}}
                                    {{--<span class="features">{{trans('messages.lblSpecial_shoes')}}</span>--}}
                                    {{--@if(strcmp($features->special_shoes_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(24)"--}}
                                    {{--onmouseout="HideDetail(24)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup24">--}}
                                    {{--<p>{!! $features->special_shoes_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup24"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}

                                    {{--******************************entrance_fee*************************************************--}}

                                    {{--@if(   isset($features->entrance_fee) && $features->entrance_fee && !($features->entrance_fee_optional) )--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/money.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" هزینه ورودی">--}}
                                    {{--<span class="features">{{trans('messages.lblEntrance_fee')}}</span>--}}
                                    {{--@if(strcmp($features->entrance_fee_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(25)"--}}
                                    {{--onmouseout="HideDetail(25)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup25">--}}
                                    {{--<p>{!! $features->entrance_fee_description !!}</p>--}}

                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup25"></span>--}}
                                    {{--@endif--}}
                                    {{--</div>--}}

                                    {{--@endif--}}
                                    {{--******************************red_crescent_relief_team*************************************************--}}
                                    {{--@if(   isset($features->red_crescent_relief_team) && $features->red_crescent_relief_team && ! ($features->red_crescent_relief_team_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/red-crescent.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title=" هلال احمر">--}}
                                    {{--<span class="features">{{trans('messages.lblRed_crescent_relief_team')}}</span>--}}
                                    {{--@if(strcmp($features->red_crescent_relief_team_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(26)"--}}
                                    {{--onmouseout="HideDetail(26)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup26">--}}
                                    {{--<p>{!! $features->red_crescent_relief_team_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup26"></span>--}}
                                    {{--@endif--}}
                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************head_lump*************************************************--}}
                                    {{--@if(   isset($features->head_lump) && $features->head_lump && ! ($features->head_lump_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/headlamp.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title="  هد لامپ">--}}
                                    {{--<span class="features">{{trans('messages.lblHead_lump')}}</span>--}}
                                    {{--@if(strcmp($features->head_lump_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(27)"--}}
                                    {{--onmouseout="HideDetail(27)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup27">--}}
                                    {{--<p>{!! $features->head_lump_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup27"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************gloves*************************************************--}}
                                    {{--@if(   isset($features->gloves) && $features->gloves && ! ($features->gloves_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/gloves.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title="   دستکش">--}}
                                    {{--<span class="features">{{trans('messages.lblGloves')}}</span>--}}
                                    {{--@if(strcmp($features->gloves_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(28)"--}}
                                    {{--onmouseout="HideDetail(28)">--}}
                                    {{--<sup>--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup28">--}}
                                    {{--<p>{!! $features->gloves_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup28"></span>--}}
                                    {{--@endif--}}
                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************transfer*************************************************--}}
                                    {{--@if(   isset($features->transfer) && $features->transfer && ! ($features->transfer_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/bus.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title="   ترانسفر">--}}
                                    {{--<span class="features">{{trans('messages.lblTransfer')}}</span>--}}
                                    {{--@if(strcmp($features->transfer_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(29)"--}}
                                    {{--onmouseout="HideDetail(29)">--}}
                                    {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup29">--}}
                                    {{--<p>{!! $features->transfer_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup29"></span>--}}
                                    {{--@endif--}}
                                    {{--</div>--}}

                                    {{--@endif--}}
                                    {{--******************************accommodation*************************************************--}}
                                    {{--@if(   isset($features->accommodation) && $features->accommodation && !($features->accommodation_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/accommodation.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title="   اقامتگاه">--}}
                                    {{--<span class="features">{{trans('messages.lblAccommodation')}}</span>--}}
                                    {{--@if(strcmp($features->accommodation_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(30)"--}}
                                    {{--onmouseout="HideDetail(30)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup30">--}}
                                    {{--<p>{!! $features->accommodation_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup30"></span>--}}
                                    {{--@endif--}}
                                    {{--</div>--}}
                                    {{--@endif--}}
                                    {{--******************************diving_suits*************************************************--}}
                                    {{--@if(   isset($features->diving_suits) && $features->diving_suits && !($features->diving_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/diving-suit.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;" title="  لباس غواصی">--}}
                                    {{--<span class="features">{{trans('messages.lblDiving_suits')}}</span>--}}
                                    {{--@if(strcmp($features->diving_suits_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(31)"--}}
                                    {{--onmouseout="HideDetail(31)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup31">--}}
                                    {{--<p>{!! $features->diving_suits_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup31"></span>--}}
                                    {{--@endif--}}
                                    {{--</div>--}}

                                    {{--@endif--}}

                                    {{--******************************other_optional*************************************************--}}
                                    {{--@if(   isset($features->other_optional) && $features->other_optional &&!($features->other_optional))--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/other_optional.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:0px;"--}}
                                    {{--title=" سایر موارد">--}}
                                    {{--<span class="features">{{trans('messages.lblOther_optional')}}</span>--}}
                                    {{--@if(strcmp($features->other_optional_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(32)"--}}
                                    {{--onmouseout="HideDetail(32)">--}}
                                    {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--</sup>--}}
                                    {{--<span class="popuptext" id="myPopup32">--}}
                                    {{--<p>{!! $features->other_optional_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup32"></span>--}}
                                    {{--@endif--}}

                                    {{--</div>--}}

                                    {{--@endif--}}

                                    {{--******************************other*************************************************--}}
                                    {{--@if(   isset($features->other) && $features->other)--}}
                                    {{--<div class="col-xs-6" style="margin-bottom: 10px">--}}
                                    {{--<img src="{{url('/SVGs_Tour/other.svg')}}" width="30px" alt=""--}}
                                    {{--style="vertical-align: middle;margin-left:5px;" title="  سایر">--}}
                                    {{--<span class="features">{{trans('messages.lblOther')}}</span>--}}
                                    {{--@if(strcmp($features->other_description,'ندارد')!=0)--}}
                                    {{--<div class="popups" onmouseover="showDetail(33)"--}}
                                    {{--onmouseout="HideDetail(33)">--}}
                                    {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                    {{--style="color: #6b63e4"></a>--}}
                                    {{--<span class="popuptext" id="myPopup33">--}}
                                    {{--<p>{!! $features->other_description !!}</p>--}}
                                    {{--</span>--}}
                                    {{--</div>--}}
                                    {{--@else--}}
                                    {{--<span id="myPopup33"></span>--}}
                                    {{--@endif--}}
                                    {{--<br><br>--}}
                                    {{--</div>--}}
                                    {{--@endif--}}

                                    <br>
                                    {{--<button onclick="cost()">cost</button>--}}
                                </div>
                                {{--</form>--}}
                            </div>
                            <hr>
                            @else
                            <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                        @endif
                        @else
                        <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                    @endif




                    <div class="row" dir="rtl">
                        <div class="col-md-12" dir="rtl">
                            <h4 class="widget-title"
                                style="margin-top: 20px;color:black;"> {{trans('messages.lblArbitrary')}}</h4>
                            <p></p>
                            <br>
                        </div>
                    </div>


                    <div class="row" dir="rtl">
                        <form class="col-md-12" name="reserve_form" role="form" method="POST"
                             {{--onsubmit="return validateMyForm();"--}}
                             action="{{ url('/transactions/storeTour') }}">
                            {{ csrf_field() }}

                            {{--//هانی پوت--}}

                            <div style="display: none;">
                                <label>این فرم را خالی بگذارید</label>
                                <input type="text" name="phone" id="phone"/>
                            </div>

                            <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                            <style>
                                .fe02 input {
                                    text-align: center;
                                    border: 1px solid;
                                }
                            </style>

                            @if(isset($features))
                                @if(count($features) > 0)

                                    <div class="alert alert-info">
                                        مواردی که در صورت درخواست کاربر تامین می شوند.
                                    </div>
                                    @foreach($features as $feature)
                                        @if($feature->is_optional == 1)


                                            <div class="col-lg-6" style="margin-bottom: 10px">
                                            <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}" width="30px" alt=""
                                            style="vertical-align: middle;margin-left:0px;" title="{{$feature->name}}">
                                            <span>{{$feature->name}}</span>
                                            @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                            <div class="popups" onmouseover="showDetail({{$feature->feature_uid}})"
                                            onmouseout="HideDetail({{$feature->feature_uid}})">
                                            <sup> <a class="fa fa-question-circle" aria-hidden="true"
                                            style="color: #6b63e4"></a>
                                            </sup>
                                            <span class="popuptext" id="myPopup_{{$feature->feature_uid}}">
                                            <p>{!! $feature->description." ".$feature->more_description  !!}</p>
                                            </span>
                                            </div>
                                            @else
                                            <span id="myPopup_{{$feature->feature_uid}}"></span>
                                            @endif

                                            <span style="float: right">
                                            <input type="checkbox" name="gps" id="gpsCheck" onchange="cost()"
                                            style="width: 20px; height: 20px;margin-left: 5px">
                                            </span>
                                            <span> {{number_format($feature->cost)}} تومان
                                            <input id="count_{{$feature->feature_uid}}" type="number" name="{{$feature->feature_uid}}_quantity" min="1" max="{{$feature->capacity}}"
                                            style="width: 35px;height: 20px" onchange="cost()" value="{{$feature->count}}">
                                            </span>

                                            </div>





                                        @endif
                                    @endforeach


                                    <div class="col-md-12 fe02" dir="rtl" id="featuresListIcons">

                                        {{--*****************************gps*******************************--}}
                                        {{--@if(   isset($features->gps) && $features->gps && ($features->gps))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/gps.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" GPS">--}}
                                        {{--<span>{{trans('messages.lblGps')}}</span>--}}
                                        {{--@if(strcmp($features->gps_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(1)"--}}
                                        {{--onmouseout="HideDetail(1)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup1">--}}
                                        {{--<p>{!! $features->gps_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup1"></span>--}}
                                        {{--@endif--}}

                                        {{--@if($features->gps_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="gps" id="gpsCheck" onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->gps_cost)}} تومان--}}
                                        {{--<input id="gpsCount" type="number" name="gps_quantity" min="1" max="99"--}}
                                        {{--style="width: 35px;height: 20px" onchange="cost()" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--******************************casket*******************************--}}
                                        {{--@if(   isset($features->casket) && $features->casket && ($features->casket_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/helmet.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title="کلاه ایمنی">--}}
                                        {{--<span>{{trans('messages.lblCasket')}}</span>--}}
                                        {{--@if(strcmp($features->casket_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(2)"--}}
                                        {{--onmouseout="HideDetail(2)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup2">--}}
                                        {{--<p>{!! $features->casket_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup2"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->casket_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="casket" id="casketCheck" onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->casket_cost)}} تومان--}}
                                        {{--<input id="casketCount" type="number" name="casket_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}

                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--************************************rope*******************************************--}}
                                        {{--@if(   isset($features->rope) && $features->rope && ($features->rope_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/rope.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title="طناب ">--}}
                                        {{--<span>{{trans('messages.lblRope')}}</span>--}}
                                        {{--@if(strcmp($features->rope_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(3)"--}}
                                        {{--onmouseout="HideDetail(3)">--}}
                                        {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup3">--}}
                                        {{--<p>{!! $features->rope_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup3"></span>--}}
                                        {{--@endif--}}

                                        {{--@if($features->rope_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="rope" id="ropeCheck" onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}

                                        {{--<span> {{number_format($features->rope_cost)}} تومان--}}
                                        {{--<input id="ropeCount" type="number" name="rope_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}

                                        {{--*******************************carabin************************************************--}}
                                        {{--@if(    isset($features->carabin) && $features->carabin && ($features->carabin_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/carabiner.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" کارابین">--}}
                                        {{--<span>{{trans('messages.lblCarabin')}}</span>--}}
                                        {{--@if(strcmp($features->carabin_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(4)"--}}
                                        {{--onmouseout="HideDetail(4)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup4">--}}
                                        {{--<p>{!! $features->carabin_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup4"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->carabin_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="carabin" id="carabinCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->carabin_cost)}} تومان--}}
                                        {{--<input id="carabinCount" type="number" name="carabin_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--*******************************eight************************************************--}}
                                        {{--@if(    isset($features->eight) && $features->eight && ($features->eight_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/eight.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" هشت فرود">--}}
                                        {{--<span>{{trans('messages.lblEight')}}</span>--}}
                                        {{--@if(strcmp($features->eight_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(5)"--}}
                                        {{--onmouseout="HideDetail(5)">--}}
                                        {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup5">--}}
                                        {{--<p>{!! $features->eight_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup5"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->eight_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="eight" id="eightCheck" onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->eight_cost)}} تومان--}}
                                        {{--<input id="eightCount" type="number" name="eight_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--*****************************harness**************************************************--}}
                                        {{--@if(    isset($features->harness) && $features->harness && ($features->harness_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/harness.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" هارنس">--}}
                                        {{--<span>{{trans('messages.lblHarness')}}</span>--}}
                                        {{--@if(strcmp($features->harness_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(6)"--}}
                                        {{--onmouseout="HideDetail(6)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup6">--}}
                                        {{--<p>{!! $features->harness_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup6"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->harness_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="harness" id="harnessCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->harness_cost)}} تومان--}}
                                        {{--<input id="harnessCount" type="number" name="harness_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************wet_suit*************************************************--}}
                                        {{--@if(    isset($features->wet_suit) && $features->wet_suit && ($features->wet_suit_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/wetsuit.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" لباس شنا">--}}
                                        {{--<span>{{trans('messages.lblWet_suit')}}</span>--}}
                                        {{--@if(strcmp($features->wet_suit_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(7)"--}}
                                        {{--onmouseout="HideDetail(7)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup7">--}}
                                        {{--<p>{!! $features->wet_suit_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup7"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->wet_suit_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="wet_suit" id="wet_suitCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->wet_suit_cost)}} تومان--}}
                                        {{--<input id="wet_suitCount" type="number" name="wet_suit_quantity" min="1"--}}
                                        {{--max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--******************************rescue_vest*************************************************--}}
                                        {{--@if(    isset($features->rescue_vest) && $features->rescue_vest && ($features->rescue_vest_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/rescue_vest.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title=" جلیقه نجات">--}}
                                        {{--<span>{{trans('messages.lblRescue_vest')}}</span>--}}
                                        {{--@if(strcmp($features->rescue_vest_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(8)"--}}
                                        {{--onmouseout="HideDetail(8)">--}}
                                        {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a></sup>--}}
                                        {{--<span class="popuptext" id="myPopup8">--}}
                                        {{--<p>{!! $features->rescue_vest_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup8"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->rescue_vest_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="rescue_vest" id="rescue_vestCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->rescue_vest_cost)}} تومان--}}
                                        {{--<input id="rescue_vestCount" type="number" name="rescue_vest_quantity" min="1"--}}
                                        {{--max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************breakfast*************************************************--}}
                                        {{--@if(    isset($features->breakfast) && $features->breakfast &&($features->breakfast_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/breakfast.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" صبحانه">--}}
                                        {{--<span>{{trans('messages.lblBreakfast')}}</span>--}}
                                        {{--@if(strcmp($features->breakfast_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(9)"--}}
                                        {{--onmouseout="HideDetail(9)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup9">--}}
                                        {{--<p>{!! $features->breakfast_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup9"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->breakfast_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="breakfast" id="breakfastCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->breakfast_cost)}} تومان--}}
                                        {{--<input id="breakfastCount" type="number" name="breakfast_quantity" min="1"--}}
                                        {{--max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************lunch*************************************************--}}
                                        {{--@if(    isset($features->lunch) && $features->lunch && ($features->lunch_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/lunch.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" نهار">--}}
                                        {{--<span>{{trans('messages.lblLunch')}}</span>--}}
                                        {{--@if(strcmp($features->lunch_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(10)"--}}
                                        {{--onmouseout="HideDetail(10)">--}}
                                        {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup10">--}}
                                        {{--<p>{!! $features->lunch_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup10"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->lunch_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="lunch" id="lunchCheck" onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->lunch_cost)}} تومان--}}
                                        {{--<input id="lunchCount" type="number" name="lunch_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--******************************dinner*************************************************--}}
                                        {{--@if(    isset($features->dinner) && $features->dinner &&($features->dinner_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/dinner.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" شام">--}}
                                        {{--<span>{{trans('messages.lblDinner')}}</span>--}}
                                        {{--@if(strcmp($features->dinner_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(11)"--}}
                                        {{--onmouseout="HideDetail(11)">--}}
                                        {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup11">--}}
                                        {{--<p>{!! $features->dinner_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup11"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->dinner_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="dinner" id="dinnerCheck" onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->dinner_cost)}} تومان--}}
                                        {{--<input id="dinnerCount" type="number" name="dinner_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}

                                        {{--******************************evening_meal*************************************************--}}
                                        {{--@if(    isset($features->evening_meal) && $features->evening_meal && ($features->evening_meal_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/evening_meal.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" عصرانه">--}}
                                        {{--<span>{{trans('messages.lblEvening_meal')}}</span>--}}
                                        {{--@if(strcmp($features->evening_meal_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(12)"--}}
                                        {{--onmouseout="HideDetail(12)">--}}
                                        {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup12">--}}
                                        {{--<p>{!! $features->evening_meal_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup12"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->evening_meal_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="evening_meal" id="evening_mealCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->evening_meal_cost)}} تومان--}}
                                        {{--<input id="evening_mealCount" type="number" name="evening_meal_quantity"--}}
                                        {{--min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}

                                        {{--******************************catering_on_the_route*************************************************--}}
                                        {{--@if(    isset($features->catering_on_the_route) && $features->catering_on_the_route &&($features->catering_on_the_route_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/catering_on_the_route.svg')}}" width="30px"--}}
                                        {{--alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title=" پذیرایی در مسیر">--}}
                                        {{--<span>{{trans('messages.lblCatering_on_the_route')}}</span>--}}
                                        {{--@if(strcmp($features->catering_on_the_route_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(13)"--}}
                                        {{--onmouseout="HideDetail(13)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup13">--}}
                                        {{--<p>{!! $features->catering_on_the_route_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup13"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->catering_on_the_route_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="catering_on_the_route" onchange="cost()"--}}
                                        {{--id="catering_on_the_routeCheck"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->catering_on_the_route_cost)}}--}}
                                        {{--تومان--}}
                                        {{--<input id="catering_on_the_routeCount" type="number"--}}
                                        {{--name="catering_on_the_route_quantity" min="1"--}}
                                        {{--onchange="cost()"--}}
                                        {{--max="99" style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}
                                        {{--******************************parking*************************************************--}}
                                        {{--@if(    isset($features->parking) && $features->parking &&($features->parking_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/parking-sign.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" پارکینگ">--}}
                                        {{--<span>{{trans('messages.lblParking')}}</span>--}}
                                        {{--@if(strcmp($features->parking_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(14)"--}}
                                        {{--onmouseout="HideDetail(14)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup14">--}}
                                        {{--<p>{!! $features->parking_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup14"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->parking_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="parking" id="parkingCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->parking_cost)}} تومان--}}
                                        {{--<input id="parkingCount" type="number" name="parking_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--******************************local_guide*************************************************--}}
                                        {{--@if(    isset($features->local_guide) && $features->local_guide && ($features->local_guide_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/local_guide.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title=" راهنمای محلی">--}}
                                        {{--<span>{{trans('messages.lblLocal_guide')}}</span>--}}
                                        {{--@if(strcmp($features->local_guide_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(15)"--}}
                                        {{--onmouseout="HideDetail(15)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup15">--}}
                                        {{--<p>{!! $features->local_guide_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup15"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->local_guide_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="local_guide" id="local_guideCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->local_guide_cost)}} تومان--}}
                                        {{--<input id="local_guideCount" type="number" name="local_guide_quantity" min="1"--}}
                                        {{--max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************plastic*************************************************--}}
                                        {{--@if(    isset($features->plastic) && $features->plastic && ($features->plastic_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/plastic.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" پلاستیک">--}}
                                        {{--<span>{{trans('messages.lblPlastic')}}</span>--}}
                                        {{--@if(strcmp($features->plastic_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(16)"--}}
                                        {{--onmouseout="HideDetail(16)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup16">--}}
                                        {{--<p>{!! $features->plastic_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup16"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->plastic_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="plastic" id="plasticCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->plastic_cost)}} تومان--}}
                                        {{--<input id="plasticCount" type="number" name="plastic_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--************************************photographer*******************************************--}}
                                        {{--@if(    isset($features->photographer) && $features->photographer &&($features->photographer_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/photographer.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" عکاس">--}}
                                        {{--<span>{{trans('messages.lblPhotographer')}}</span>--}}
                                        {{--@if(strcmp($features->photographer_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(17)"--}}
                                        {{--onmouseout="HideDetail(17)">--}}
                                        {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup17">--}}
                                        {{--<p>{!! $features->photographer_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup17"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->photographer_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="photographer" id="photographerCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}

                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->photographer_cost)}} تومان--}}
                                        {{--<input id="photographerCount" type="number" name="photographer_quantity"--}}
                                        {{--min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************ground_cloth*************************************************--}}
                                        {{--@if(   isset($features->ground_cloth) &&  $features->ground_cloth && ($features->ground_cloth_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/ground_cloth.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" زیر انداز">--}}
                                        {{--<span>{{trans('messages.lblGround_cloth')}}</span>--}}
                                        {{--@if(strcmp($features->ground_cloth_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(18)"--}}
                                        {{--onmouseout="HideDetail(18)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup18">--}}
                                        {{--<p>{!! $features->ground_cloth_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup18"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->ground_cloth_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="ground_cloth" id="ground_clothCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->ground_cloth_cost)}} تومان--}}
                                        {{--<input id="ground_clothCount" type="number" name="ground_cloth_quantity"--}}
                                        {{--min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}

                                        {{--******************************music*************************************************--}}
                                        {{--@if(    isset($features->music) && $features->music && ($features->music_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/musica.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" موسیقی">--}}
                                        {{--<span>{{trans('messages.lblMusic')}}</span>--}}
                                        {{--@if(strcmp($features->music_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(19)"--}}
                                        {{--onmouseout="HideDetail(19)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup19">--}}
                                        {{--<p>{!! $features->music_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup19"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->music_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="music" id="musicCheck" onchange="cost()"--}}

                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->music_cost)}} تومان--}}
                                        {{--<input id="musicCount" type="number" name="music_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}
                                        {{--******************************tent*************************************************--}}
                                        {{--@if(    isset($features->tent) && $features->tent && ($features->tent_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/tent1.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" چادر">--}}
                                        {{--<span>{{trans('messages.lblTent')}}</span>--}}
                                        {{--@if(strcmp($features->tent_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(20)"--}}
                                        {{--onmouseout="HideDetail(20)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup20">--}}
                                        {{--<p>{!! $features->tent_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup20"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->tent_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="tent" id="tentCheck" onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->tent_cost)}} تومان--}}
                                        {{--<input id="tentCount" type="number" name="tent_quantity" min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}

                                        {{--******************************professional_guide*************************************************--}}
                                        {{--@if(    isset($features->professional_guide) && $features->professional_guide && ($features->professional_guide_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/professional_guide.svg')}}" width="30px"--}}
                                        {{--alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title=" راهنمای حرفه ایی">--}}
                                        {{--<span>{{trans('messages.lblProfessional_guide')}}</span>--}}
                                        {{--@if(strcmp($features->professional_guide_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(21)"--}}
                                        {{--onmouseout="HideDetail(21)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup21">--}}
                                        {{--<p>{!! $features->professional_guide_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup21"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->professional_guide_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="professional_guide" onchange="cost()"--}}
                                        {{--id="professional_guideCheck"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->professional_guide_cost)}} تومان--}}
                                        {{--<input id="professional_guideCount" type="number"--}}
                                        {{--name="professional_guide_quantity" min="1"--}}
                                        {{--onchange="cost()"--}}
                                        {{--max="99" style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}


                                        {{--******************************sleeping_bag*************************************************--}}
                                        {{--<sup class="star">*</sup>--}}
                                        {{--@if(    isset($features->sleeping_bag) && $features->sleeping_bag && ($features->sleeping_bag_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/sleeping-bag.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" کیسه خواب">--}}
                                        {{--<span>{{trans('messages.lblSleeping_bag')}}</span>--}}
                                        {{--@if(strcmp($features->sleeping_bag_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(22)"--}}
                                        {{--onmouseout="HideDetail(22)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}

                                        {{--</sup>--}}

                                        {{--<span class="popuptext" id="myPopup22">--}}
                                        {{--<p>{!! $features->sleeping_bag_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup22"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->sleeping_bag_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="sleeping_bag" id="sleeping_bagCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->sleeping_bag_cost)}} تومان--}}
                                        {{--<input id="sleeping_bagCount" type="number" name="sleeping_bag_quantity"--}}
                                        {{--min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--******************************insurance*************************************************--}}
                                        {{--@if(    isset($features->insurance) && $features->insurance && ($features->insurance_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/insurance.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" بیمه">--}}
                                        {{--<span>{{trans('messages.lblInsurance')}}</span>--}}
                                        {{--@if(strcmp($features->insurance_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(23)"--}}
                                        {{--onmouseout="HideDetail(23)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup23">--}}
                                        {{--<p>{!! $features->insurance_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup23"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->insurance_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="insurance" id="insuranceCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->insurance_cost)}} تومان--}}
                                        {{--<input id="insuranceCount" type="number" name="insurance_quantity" min="1"--}}
                                        {{--max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--******************************special_shoes*************************************************--}}

                                        {{--@if(   isset($features->special_shoes) &&  $features->special_shoes &&($features->special_shoes_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/special_shoes.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" کفش مخصوص">--}}
                                        {{--<span>{{trans('messages.lblSpecial_shoes')}}</span>--}}
                                        {{--@if(strcmp($features->special_shoes_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(24)"--}}
                                        {{--onmouseout="HideDetail(24)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup24">--}}
                                        {{--<p>{!! $features->special_shoes_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup24"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->special_shoes_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="special_shoes" id="special_shoesCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span>{{number_format($features->special_shoes_cost)}} تومان--}}
                                        {{--<input id="special_shoesCount" type="number" name="special_shoes_quantity"--}}
                                        {{--min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}

                                        {{--******************************entrance_fee*************************************************--}}

                                        {{--@if(   isset($features->entrance_fee) &&  $features->entrance_fee &&($features->entrance_fee_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/money.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title=" هزینه ورودی">--}}
                                        {{--<span>{{trans('messages.lblEntrance_fee')}}</span>--}}
                                        {{--@if(strcmp($features->entrance_fee_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(25)"--}}
                                        {{--onmouseout="HideDetail(25)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup25">--}}
                                        {{--<p>{!! $features->entrance_fee_description !!}</p>--}}

                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup25"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->entrance_fee_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="entrance_fee" id="entrance_feeCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span> {{number_format($features->entrance_fee_cost)}} تومان--}}
                                        {{--<input id="entrance_feeCount" type="number" name="entrance_fee_quantity"--}}
                                        {{--min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}
                                        {{--******************************red_crescent_relief_team*************************************************--}}
                                        {{--@if(   isset($features->red_crescent_relief_team) &&  $features->red_crescent_relief_team && ($features->red_crescent_relief_team_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/red-crescent.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title=" هلال احمر">--}}
                                        {{--<span>{{trans('messages.lblRed_crescent_relief_team')}}</span>--}}
                                        {{--@if(strcmp($features->red_crescent_relief_team_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(26)"--}}
                                        {{--onmouseout="HideDetail(26)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup26">--}}
                                        {{--<p>{!! $features->red_crescent_relief_team_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup26"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->red_crescent_relief_team_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="red_crescent_relief_team" onchange="cost()"--}}
                                        {{--id="red_crescent_relief_teamCheck"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span>{{number_format($features->red_crescent_relief_team_cost)}}--}}
                                        {{--تومان--}}
                                        {{--<input id="red_crescent_relief_teamCount" type="number"--}}
                                        {{--name="red_crescent_relief_team_quantity"--}}
                                        {{--min="1" max="99" onchange="cost()" style="width: 35px;height: 20px"--}}
                                        {{--value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************head_lump*************************************************--}}
                                        {{--@if(   isset($features->head_lump) &&  $features->head_lump && ($features->head_lump_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/headlamp.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title="  هد لامپ">--}}
                                        {{--<span>{{trans('messages.lblHead_lump')}}</span>--}}
                                        {{--@if(strcmp($features->head_lump_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(27)"--}}
                                        {{--onmouseout="HideDetail(27)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup27">--}}
                                        {{--<p>{!! $features->head_lump_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup27"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->head_lump_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="head_lump" id="head_lumpCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span>{{number_format($features->head_lump_cost)}} تومان--}}
                                        {{--<input id="head_lumpCount" type="number" name="head_lump_quantity" min="1"--}}
                                        {{--max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************gloves*************************************************--}}
                                        {{--@if(   isset($features->gloves) &&  $features->gloves && ($features->gloves_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/gloves.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;" title="   دستکش">--}}
                                        {{--<span>{{trans('messages.lblGloves')}}</span>--}}
                                        {{--@if(strcmp($features->gloves_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(28)"--}}
                                        {{--onmouseout="HideDetail(28)">--}}
                                        {{--<sup>--}}
                                        {{--<a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup28">--}}
                                        {{--<p>{!! $features->gloves_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup28"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->gloves_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="gloves" id="glovesCheck" onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span>{{number_format($features->gloves_cost)}} تومان--}}
                                        {{--<input id="glovesCount" type="number" name="gloves_quantity" min="1"--}}
                                        {{--max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}
                                        {{--@else--}}
                                        {{--<div style="float: right">--}}
                                        {{--<input type="checkbox" disabled--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px;color: red">--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--<br><br>--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************transfer*************************************************--}}
                                        {{--@if(   isset($features->transfer) &&  $features->transfer &&($features->transfer_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/bus.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title="   وسیله حمل و نقل">--}}
                                        {{--<span>{{trans('messages.lblTransfer')}}</span>--}}
                                        {{--@if(strcmp($features->transfer_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(29)"--}}
                                        {{--onmouseout="HideDetail(29)">--}}
                                        {{--<sup><a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup29">--}}
                                        {{--<p>{!! $features->transfer_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup29"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->transfer_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="transfer" id="transferCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span>{{number_format($features->transfer_cost)}} تومان--}}
                                        {{--<input id="transferCount" type="number" name="transfer_quantity" min="1"--}}
                                        {{--max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}
                                        {{--@else--}}
                                        {{--<div style="float: right">--}}
                                        {{--<input type="checkbox" disabled--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px;color: red">--}}
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--</div>--}}
                                        {{--<br><br>--}}
                                        {{--@endif--}}
                                        {{--******************************accommodation*************************************************--}}
                                        {{--@if(   isset($features->accommodation) &&  $features->accommodation && ($features->accommodation_optional))--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/accommodation.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title="   اقامتگاه">--}}
                                        {{--<span>{{trans('messages.lblAccommodation')}}</span>--}}
                                        {{--@if(strcmp($features->accommodation_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(30)"--}}
                                        {{--onmouseout="HideDetail(30)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup30">--}}
                                        {{--<p>{!! $features->accommodation_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup30"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->accommodation_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="accommodation" id="accommodationCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span>{{number_format($features->accommodation_cost)}} تومان--}}
                                        {{--<input id="accommodationCount" type="number" name="accommodation_quantity"--}}
                                        {{--min="1"--}}
                                        {{--onchange="cost()"--}}
                                        {{--max="99" style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}

                                        {{--</div>--}}
                                        {{--@endif--}}
                                        {{--******************************diving_suits*************************************************--}}
                                        {{--@if(   isset($features->diving_suits) &&  $features->diving_suits &&($features->diving_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/diving-suit.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title="  لباس غواصی">--}}
                                        {{--<span>{{trans('messages.lblDiving_suits')}}</span>--}}
                                        {{--@if(strcmp($features->diving_suits_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(31)"--}}
                                        {{--onmouseout="HideDetail(31)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup31">--}}
                                        {{--<p>{!! $features->diving_suits_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup31"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->diving_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="diving_suits" id="diving_suitsCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span>{{number_format($features->diving_suits_cost)}} تومان--}}
                                        {{--<input id="diving_suitsCount" type="number" name="diving_suits_quantity"--}}
                                        {{--min="1" max="99"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 35px;height: 20px" value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}

                                        {{--******************************other_optional*************************************************--}}
                                        {{--@if(   isset($features->other_optional) &&  $features->other_optional &&($features->other_optional) )--}}
                                        {{--<div class="col-lg-6" style="margin-bottom: 10px">--}}
                                        {{--<img src="{{url('/SVGs_Tour/other_optional.svg')}}" width="30px" alt=""--}}
                                        {{--style="vertical-align: middle;margin-left:0px;"--}}
                                        {{--title=" سایر موارد">--}}
                                        {{--<span>{{trans('messages.lblOther_optional')}}</span>--}}
                                        {{--@if(strcmp($features->other_optional_description,'ندارد')!=0)--}}
                                        {{--<div class="popups" onmouseover="showDetail(32)"--}}
                                        {{--onmouseout="HideDetail(32)">--}}
                                        {{--<sup> <a class="fa fa-question-circle" aria-hidden="true"--}}
                                        {{--style="color: #6b63e4"></a>--}}
                                        {{--</sup>--}}
                                        {{--<span class="popuptext" id="myPopup32">--}}
                                        {{--<p>{!! $features->other_optional_description !!}</p>--}}
                                        {{--</span>--}}
                                        {{--</div>--}}
                                        {{--@else--}}
                                        {{--<span id="myPopup32"></span>--}}
                                        {{--@endif--}}
                                        {{--@if($features->other_optional)--}}
                                        {{--<span style="float: right">--}}
                                        {{--<input type="checkbox" name="other_optional" id="other_optionalCheck"--}}
                                        {{--onchange="cost()"--}}
                                        {{--style="width: 20px; height: 20px;margin-left: 5px">--}}
                                        {{--</span>--}}
                                        {{--<span>--}}
                                        {{--@if($features->other_optional_cost == 0)--}}
                                        {{--- رایگان--}}

                                        {{--@else--}}
                                        {{--{{number_format($features->other_optional_cost)}} تومان--}}

                                        {{--@endif--}}

                                        {{--<input id="other_optionalCount" type="number" name="other_optional_quantity"--}}
                                        {{--min="1" max="99" onchange="cost()" style="width: 35px;height: 20px"--}}
                                        {{--value="1">--}}
                                        {{--</span>--}}

                                        {{--@endif--}}
                                        {{--</div>--}}

                                        {{--@endif--}}

                                        <br>
                                    </div>


                                @endif
                            @endif






                            <div>

                                <!-- CONTACT -->
                                <div class="page-section" id="contact">
                                    <div id="MeysamTemp" style="margin-top: 20px">
                                        <div>
                                            {{--<h4 class="widget-title" style="color: black;margin-top: 5px">رزرواسیون</h4>--}}
                                            <fieldset style="padding-top: 30px" id="contact2">
                                                <h4 class="widget-title" style="color: black;margin-top: 5px">رزرواسیون</h4>

                                                {{--<div class="row" id="test"--}}
                                                {{--style="alignment: center;margin-top: 10px;margin-bottom: 10px">--}}
                                                {{--<div class="col-md-3" style="border:2px solid;border-radius: 4px;float: right">--}}
                                                {{--<span style="margin-left: 5px"> مجموع کل:</span>--}}
                                                {{--<span id="demo"> </span>--}}

                                                {{--<span>تومان</span>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}


                                                <div class="row">
                                                    <div class="col-md-12" dir="rtl" id="pictures">

                                                        <div class="col-md-6" style="float: right;" id="persInfo_0">
                                                            <fieldset>
                                                                <legend>اطلاعات رزرو کننده:</legend>
                                                                <div class="form-group">
                                                                    <label class="required"
                                                                           for="name_and_family">    {{trans('messages.lblNameAndFamily')}}

                                                                    </label>
                                                                    <div>
                                                                        <input id="name_and_family_0" type="text"
                                                                               name="name_and_family_0"
                                                                               title="نام و نام خانوادگی را وارد کنید.  "
                                                                               value="{{ old('name_and_family') }}"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="required"
                                                                           for="national_code">  {{trans('messages.lblNationalCode')}}</label>
                                                                    <div>
                                                                        <input id="national_code_0" type="text"
                                                                               name="national_code_0"
                                                                               title="کد ملی را وارد کنید."
                                                                               value="{{ old('national_code') }}"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="required"
                                                                           for="mobile">    {{trans('messages.lblMobile')}}</label>
                                                                    <div>
                                                                        <input id="mobile_0" type="text"
                                                                               name="mobile_0"
                                                                               title="شماره تلفن همراه را وارد کنید."
                                                                               value="{{ old('mobile') }}"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="required"
                                                                           for="birth_date"> {{trans('messages.lblBirthDate')}} </label>
                                                                    {{--<p style="font-size:8px">برای نمایش سال مورد نطر--}}
                                                                    {{--بر روی سال جاری دابل کلیک کنید.</p>--}}

                                                                    <div>

                                                                        <input id="start_date_show_0" type="text"
                                                                               class="initial-value-example"
                                                                               required
                                                                               title=" تاریخ تولد را مشخص کنید"
                                                                               autocomplete="off">
                                                                        <input id="birth_date_0" name="birth_date_0"
                                                                               style="display: none">
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email">    {{trans('messages.lblEmail')}}
                                                                        (اختیاری)</label>

                                                                    <div>
                                                                        <input id="email_0" type="text"
                                                                               name="email_0"
                                                                               value="{{ old('email') }}">
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="height: 55px;" dir="rtl">

                                                                </div>

                                                            </fieldset>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row"
                                                     style="alignment: center;border-bottom: solid grey;margin-top: 10px;">
                                                    <div class="col-md-6 col-md-offset-3">
                                                        <button class="col-md-6 col-md-offset-3"
                                                                style="margin-top: 10px;background-color: white;border: solid 1px grey;margin-bottom: 10px"
                                                                id="button1" type="button" onclick="Add();"
                                                                title="جدید">
                                                            <i class="fa fa-plus"
                                                               style="font-size:40px;color:green"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-md-12" style="margin-top: 20px;">
                                                        <div class="col-md-12 form-group" id="captchSection">
                                                            <p style="text-align: center">
                                                                <input type="checkbox" id="chk_terms"
                                                                       name="chk_terms" required>
                                                                تایید می کنم که
                                                                <a target="_blank" href="{{url('/termsOfUse') }}">
                                                                    شرایط
                                                                    و
                                                                    قوانین </a> استفاده از سایت را پذیرفته ام.
                                                            </p>
                                                            {{--<div class="form-group refereshrecapcha">--}}
                                                            {{--{!! captcha_img('flat') !!}--}}
                                                            {{--</div>--}}
                                                            {{--<button class="btn" onclick="refreshCaptcha()">{{trans('messages.btnRefresh')}}</button>--}}
                                                            {{--<br>--}}
                                                            {{--{{trans('messages.lblCaptcha')}}:<input type="text" name="captcha" id="captcha">--}}



                                                            {{--<script src="https://authedmine.com/lib/captcha.min.js"--}}
                                                            {{--async></script>--}}
                                                            {{--<div id="sd" data-callback="myCaptchaCallback"--}}
                                                            {{--data-disable-elements="button[type=submit]"--}}
                                                            {{--data-whitelabel="false" class="coinhive-captcha"--}}
                                                            {{--data-hashes="256"--}}
                                                            {{--data-key="SlWFDeLelUF8Fyc9i5wVhECIBuzzZWeH">--}}
                                                            {{--<em>{{trans('messages.msgCaptchaLoading')}}<br>--}}
                                                            {{--{{trans('messages.msgCaptchaLoadingWait')}}</em>--}}
                                                            {{--</div>--}}

                                                        </div>
                                                        <div class="col-md-12 form-group">
                                                            <div class="alert alert-warning">
                                                                فردی که مشخصات او در این فرم به عنوان رزرو کننده ثبت
                                                                شده بایستی
                                                                برای
                                                                اعزام به مقصد حضور داشته باشد و کارت ملی خود و تمامی
                                                                افراد ثبت
                                                                شده
                                                                را نیز به همراه داشته
                                                                باشد.
                                                            </div>
                                                            <div class="alert alert-danger">
                                                                با فشردن دکمه ثبت و پرداخت شما متعهد به رعایت همه ی
                                                                قوانین
                                                                اخلاقی و
                                                                شرعی و عرفی جمهوری اسلامی ایران خواهید بود و مسئولیت
                                                                هرگونه
                                                                مشکلی که
                                                                در اثر رعایت نکردن این قوانین به وجود بیاید به عهده
                                                                خود شما می
                                                                باشد.
                                                            </div>
                                                        </div>
                                                        <div>


                                                            @if(!$is_ended)
                                                            <div class="row" id="test"
                                                                 style="alignment: center;margin-top: 10px;margin-bottom: 10px">
                                                                <div class="col-md-12"
                                                                     style="text-align: center;font-size: large">
                                                                    <span style="margin-left: 5px">  قیمت نهایی:</span>
                                                                    <span id="demo"> </span>

                                                                    <span>تومان</span>
                                                                </div>
                                                            </div>


                                                            <button type="submit" class="btn btn-primary"
                                                                    style="min-width:30%;display: block;margin-left: auto;margin-right: auto;">
                                                                ثبت و پرداخت
                                                            </button>
                                                                @else
                                                                <div class="row" id="test"
                                                                     style="alignment: center;margin-top: 10px;margin-bottom: 10px">
                                                                    <div class="col-md-12"
                                                                         style="text-align: center;font-size: large">
                                                                        <span style="margin-left: 5px"> برگزار شد </span>

                                                                    </div>
                                                                </div>


                                                                @endif

                                                            <br>
                                                            <a href="{{url('/')}}" class="btn btn-primary"
                                                               style="max-width:30%;display: block;margin-left: auto;margin-right: auto">  {{trans('messages.btnBack')}} </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <script>
                                                // document.getElementById("demo").innerHTML = cost();


                                                var person_count = 1;

                                                function Add() {
                                                    person_count = person_count + 1;
                                                    // cost();
                                                    document.getElementById("demo").innerHTML = cost();

                                                    var count = $("#pictures > div").length;

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


                                                    var Input = " <div class=\"col-md-6\" id='" + persId + "' style=\"float: right\" >" +
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
                                                        "                     <div class=\"form-group\">\n" +
                                                        "                      <label for=\"email\">    {{trans('messages.lblEmail')}}\n" +
                                                        "  (اختیاری)</label>\n" +
                                                        "\n" +
                                                        " <div>\n" +
                                                        "   <input id='" + emailId + "' type=\"text\" name='" + email_name + "'\n" +
                                                        "   value=\"{{ old('email') }}\">\n" +
                                                        "    </div></div>" +
                                                        " <div class=\"row\" style=\"height: 55px;alignment: center\"  id='" + test + "'>"
                                                        + "<div class=\" col-md-6 col-md-offset-3\"  >" +
                                                        "" + "<button class=\" col-md-6 col-md-offset-3\" id='" + btnId + "' style=\"margin-top: 10px;background-color: white;border: solid 1px grey\"  type=\"button\" title=\"حذف\"  onclick=\"Remove();\"> <i class=\"fa fa-remove\" style=\"font-size:40px;color:red\"></i>" +
                                                        "    </button></div" +
                                                        "</div>" +
                                                        "</div>" +
                                                        "</fieldset>";


                                                    $('#pictures').append(Input);

                                                    btnId = "btn_" + (count - 1);
                                                    $("#" + btnId).remove();
                                                    calender();
                                                    // document.getElementById("demo").innerHTML = cost();

                                                }

                                                function Remove() {
                                                    person_count = person_count - 1;
                                                    document.getElementById("demo").innerHTML = cost();

                                                    var count = $("#pictures > div").length;
                                                    count = count - 1;
                                                    persId = "persInfo_" + count;
                                                    $("#" + persId).remove();

                                                    count = count - 1;
                                                    persId = "persInfo_" + count;

                                                    if (count != 0) {

                                                        var picRemoveButton =
                                                            "<div class=\"row\"  style=\"alignment: center;\"><div class=\"col-md-6 col-md-offset-3\">" +
                                                            " <button class=\"col-md-6 col-md-offset-3\" id='" + btnId + "' style=\"margin-top: 5px\" type=\"button\"  onclick=\"Remove();\"> <i class=\"fa fa-remove\" style=\"font-size:40px;color:red\"></i>" +
                                                            " </button></div> </div>";
                                                        test = "test_" + count;
                                                        // alert(test);
                                                        $("#" + test).append(picRemoveButton);

                                                    }

                                                    // cost();


                                                }

                                            </script>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>

                        </form>

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

        <script>
            $(this).attr('data-id');

        </script>
        @endsection


        @section('page-js-script')
            <script type="text/javascript">


                var todayDateMiladi = "<?php echo Carbon::now()->format('Y-m-d'); ?>";
                var todayDateShamsi = "<?php echo $today; ?>";
                var todayDateShamsi2 = parseInt((todayDateShamsi.replace('-', '')).replace('-', ''));
                var minage = "<?php echo $tour->minimum_age; ?>";

                var maxage = "<?php echo $tour->maximum_age; ?>";


                var ph = [];


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
                var originLatitude = "<?php echo $tour->gathering_place->latitude; ?>";
                var originLongitude = "<?php echo $tour->gathering_place->longitude; ?>";
                var address = "<?php echo $tour->gathering_place->address; ?>";

                var goalLatitude = "<?php echo $tour->tour_address->latitude; ?>";
                var goalLongitude = "<?php echo $tour->tour_address->longitude; ?>";
                var title = "<?php echo $tour->title; ?>";


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
                                iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
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
                                iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
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
                    var count = $("#pictures > div").length;
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


                // String.prototype.toPersianDigits = function () {
                //     var id = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
                //     return this.replace(/[0-9]/g, function (w) {
                //         return id[+w];
                //     });
                // };


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

                    // var count = $("#pictures > div").length;
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


                // function myCaptchaCallback() {

                // $('div.verified-container div.verified-text').text('New text');
                // $('.verify-me-text').text('New text');
                // $('.verify-me-text').html('New text');
                // $('.verified-text').html('New text');

                // }

                // function showDetail(x) {
                //     for (i = 1; i < 32; i++) {
                //         var popup = document.getElementById("myPopup" + i);
                //         if (popup != null && popup.classList.contains("show")) {
                //             popup.classList.remove("show");
                //         }
                //     }
                //     var popup = document.getElementById("myPopup" + x);
                //     popup.classList.toggle("show");
                //
                // }

                // function HideDetail(x) {
                //
                //     for (i = 1; i < 32; i++) {
                //         var popup = document.getElementById("myPopup" + i);
                //         if (popup != null && popup.classList.contains("show")) {
                //             popup.classList.remove("show");
                //
                //         }
                //
                //     }
                //     var popup = document.getElementById("myPopup" + x);
                //     if (popup != null && popup.classList.contains("show")) {
                //         popup.classList.remove("show");
                //     }
                //
                // }

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


                // // MyPopup
                // function showDetail2(x) {
                //     for (i = 1; i < 32; i++) {
                //         var popup = document.getElementById("MyPopup" + i);
                //         if (popup != null && popup.classList.contains("show")) {
                //             popup.classList.remove("show");
                //         }
                //     }
                //     var popup = document.getElementById("MyPopup" + x);
                //     popup.classList.toggle("show");
                //
                // }
                //
                // function HideDetail2(x) {
                //
                //     for (i = 1; i < 32; i++) {
                //         var popup = document.getElementById("MyPopup" + i);
                //         if (popup != null && popup.classList.contains("show")) {
                //             popup.classList.remove("show");
                //
                //         }
                //
                //     }
                //     var popup = document.getElementById("MyPopup" + x);
                //     if (popup != null && popup.classList.contains("show")) {
                //         popup.classList.remove("show");
                //     }
                //
                // }

                // cost();

                function cost() {

                    costfeature = 0;

                    // costfeature = costfeature + (other_optional_cost * other_optional_number);
                    var base_cost = "<?php echo($tour->cost) ?>";
                    total_cost = costfeature + (person_count * base_cost);
                    document.getElementById("demo").innerHTML = total_cost;
                    return (total_cost);


                }

                document.getElementById("demo").innerHTML = cost();

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

            </script>
@stop