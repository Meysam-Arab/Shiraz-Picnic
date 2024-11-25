@extends('layouts.master_tour')
@section('title',  trans('messages.tltTourDetails'))

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
    $temp_start_date = \Carbon\Carbon::parse($tour->miladi_start_date_time,"Asia/Tehran");
//    $temp_start_date = \Carbon\Carbon::parse($temp_start_date->toDateString());
    if ($temp_start_date->lt(\Carbon\Carbon::now("Asia/Tehran")))
        $is_ended = true;

//    $temp_deadline_date_time = new Carbon();
//    $temp_deadline_date_time->setTimezone("Asia/Tehran");
    $temp_deadline_date_time = \Carbon\Carbon::parse($tour->miladi_deadline_date_time,"Asia/Tehran");
//    $temp_deadline_date_time->setTimezone("Asia/Tehran");
//    $temp_deadline_date_time = \Carbon\Carbon::parse($temp_deadline_date_time->toDateTimeString());
    if ($temp_deadline_date_time->lt(\Carbon\Carbon::now("Asia/Tehran")))
        $is_ended = true;

    if($tour->remaining_capacity <= 0)
        $is_ended = true;



//        Log::info('Carbon::now:'.json_encode(Carbon::now("Asia/Tehran")));
//        Log::info('miladi_deadline_date_time:'.json_encode($temp_deadline_date_time));



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
            border: 1px solid #3a87ad;
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
                    src="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/1/3.33')}}" alt=""
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
                            <div class="row" style="margin-top: 20px; margin-bottom: 20px">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <div class="row">
                                        @foreach($leaders as $leader)
                                            <div class=" col-md-6" style="margin-top: 10px; margin-bottom: 10px">
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
                            <br>
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
                                    $iparr = explode(" ", $ip);
                                    echo($iparr[0]);

                                    ?>
                                    <br>
                                    <?php
                                    $ip = $tour->start_date_time; // some IP address
                                    $iparr = explode(" ", $ip);
                                    echo($iparr[1]);

                                    ?>

                                </th>
                                <th style="border-left: 2px solid #b6b6b6;">
                                    <img src="{{url('/SVGs_Tour/clock.svg')}}" width="30px" alt=""
                                         title=" تاریخ و ساعت بازگشت"><br>
                                    <h3>بازگشت</h3>
                                    <hr>

                                    <?php
                                    $ip = $tour->end_date_time; // تاریخ بازگشت
                                    $iparr = explode(" ", $ip);
                                    echo($iparr[0]);

                                    ?>
                                    <br>
                                    <?php
                                    $ip = $tour->end_date_time; // ساعت بازگشت
                                    $iparr = explode(" ", $ip);
                                    echo($iparr[1]);

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
                                        {{--<div class="col-md-4">--}}
                                            {{--<h3> ظرفیت باقی مانده:</h3><br>--}}
                                            {{--<div class="col-md-12">--}}
                                                {{--<div class="circle" id="remaincapacity">--}}
                                                    {{--<span>{{$tour->remaining_capacity}}</span></div>--}}
                                            {{--</div>--}}

                                        {{--</div>--}}
                                        <div class="col-md-6">
                                            <h3> ظرفیت کل:</h3><br>
                                            <div class="col-md-12">
                                                <div class="circle" style=" "><span>{{$tour->total_capacity}}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h3>درجه سختی:</h3><br>
                                            <div class="col-md-12">
                                                <div class="circle"><span>{{$tour->hardship_level}}</span></div>
                                            </div>

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
                            <tr style="border-top: 3px solid #b6b6b6">
                                <td colspan="5" style="text-align: right">
                                    <?php
                                    $deadline = $tour->deadline_date_time; // تاریخ مهلت ثبت نام
                                    $deadline = explode(" ", $deadline);

                                    $deadline_date= $deadline[0];
                                    $deadline_hour= $deadline[1];
                                    ?>

                                    <span style="padding-right: 20px;color:red;">مهلت ثبت نام تا: </span>{{$deadline_date}}<span style="padding-right: 20px;color:red;"> ساعت: </span>{{$deadline_hour}}
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
                        <?php
                        $is_exist = false;
                        ?>
                        @foreach($features as $feature)
                            @if($feature->is_required == 1)
                                <?php
                                $is_exist = true;
                                ?>
                                <div class="col-xs-6" style="margin-bottom: 10px">
                                    <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}"
                                         width="30px" alt=""
                                         style="vertical-align: middle;margin-left:0px;" title="{{$feature->name}}">
                                    <span class="features">{{$feature->name}}</span>
                                    @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                        <div class="Popups" onmouseover="showDetail({{$feature->feature_uid}})"
                                             onmouseout="HideDetail({{$feature->feature_uid}})">
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
                                @if(!$is_exist)
                                    <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
                                @endif
                            </div>
                        </div>

                        <hr>

                        @else
                        <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
                    @endif
                    @else
                    <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
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
                                <?php
                                    $is_exist = false;
                                ?>

                                @foreach($features as $feature)
                                    @if(($feature->is_required == 0) && ($feature->is_optional == 0))
                                        <?php
                                        $is_exist = true;
                                        ?>
                                        <div class="col-xs-6" style="margin-bottom: 10px">
                                            <div class="col-md-2" style="float: right">
                                                <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}"
                                                     width="30px" alt=""
                                                     style="vertical-align: middle;margin-left:0px;"
                                                     title="{{$feature->name}}">
                                                <span class="features">{{$feature->name}}</span>
                                                @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                                    <div class="popups"
                                                         onmouseover="showDetail({{$feature->feature_uid}})"
                                                         onmouseout="HideDetail({{$feature->feature_uid}})">
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

                                            @if(!$is_exist)
                                                <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
                                            @endif

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

                                                <br>
                                                {{--<button onclick="calculateCost()">cost</button>--}}
                                            </div>
                                            {{--</form>--}}
                                        </div>
                                        <hr>
                                    @else
                                        <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
                                        <input type="hidden" id="tour_id" name="tour_id" value="{{$tour->tour_id}}">
                                    @endif
                            @else
                                    <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
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
                                                    <div id="buyableFeatrues">
                                                        <?php
                                                        $buyableCount = 0;

                                                        ?>
                                                        @foreach($features as $feature)
                                                            @if($feature->is_optional == 1)

                                                                    <?php
                                                                    $remainingCapacity = $feature->capacity - $feature->count;
                                                                    ?>

                                                                                    <div id="buyableFeatrue_{{$buyableCount}}">


                                                                    <input type="text" id="buyable_{{$buyableCount}}" name="buyable_{{$buyableCount}}"
                                                                           value="{{$feature->feature_uid}}"
                                                                           style="display: none">
                                                                    <input type="text" id="buyable_cost_{{$buyableCount}}"
                                                                           value="{{$feature->cost}}"
                                                                           style="display: none">
                                                                    <div class="col-lg-6" style="margin-bottom: 10px">
                                                                        <img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}"
                                                                             width="30px" alt=""
                                                                             style="vertical-align: middle;margin-left:0px;"
                                                                             title="{{$feature->name}}">
                                                                        <span>{{$feature->name}}</span>
                                                                        @if(strcmp($feature->description." ".$feature->more_description ,' ')!=0)
                                                                            <div class="popups"
                                                                                 onmouseover="showDetail({{$feature->feature_uid}})"
                                                                                 onmouseout="HideDetail({{$feature->feature_uid}})">
                                                                                <sup> <a class="fa fa-question-circle"
                                                                                         aria-hidden="true"
                                                                                         style="color: #6b63e4"></a>
                                                                                </sup>
                                                                                <span class="popuptext"
                                                                                      id="myPopup_{{$feature->feature_uid}}">
                                                                                        <p>{!! $feature->description." ".$feature->more_description." ظرفیت باقی مانده : ".$remainingCapacity  !!}</p>
                                                                                </span>
                                                                            </div>
                                                                        @else
                                                                            <span id="myPopup_{{$feature->feature_uid}}"></span>
                                                                        @endif

                                                                        <span style="float: right">

                                                                              @if(strcmp($remainingCapacity,'0')  == 0)

                                                                                      <input type="checkbox" name="chk_{{$feature->feature_uid}}"
                                                                                             id="chk_{{$feature->feature_uid}}"
                                                                                             onchange="calculateCost()"
                                                                                             style="width: 20px; height: 20px;margin-left: 5px;pointer-events:none;" disabled>
                                                                                @else
                                                                                <input type="checkbox" name="chk_{{$feature->feature_uid}}"
                                                                                       id="chk_{{$feature->feature_uid}}"
                                                                                       onchange="calculateCost()"
                                                                                       style="width: 20px; height: 20px;margin-left: 5px;">
                                                                                @endif

                                                                        </span>
                                                                        <span> {{number_format($feature->cost)}} تومان


                                                                            @if(strcmp($remainingCapacity,'0')  == 0)
                                                                                     <input id="quantity_{{$feature->feature_uid}}" type="number"
                                                                                            name="quantity_{{$feature->feature_uid}}" min="1"
                                                                                            max="{{$remainingCapacity}}"
                                                                                            min="1"
                                                                                            style="width: 35px;height: 20px"
                                                                                            onchange="calculateCost()"
                                                                                            value="1" style="pointer-events:none;" disabled>
                                                                     @else
                                                                                             <input id="quantity_{{$feature->feature_uid}}" type="number"
                                                                                                    name="quantity_{{$feature->feature_uid}}" min="1"
                                                                                                    max="{{$remainingCapacity}}"
                                                                                                    min="1"
                                                                                                    style="width: 35px;height: 20px"
                                                                                                    onchange="calculateCost()"
                                                                                                    value="1">
                                                                     @endif


                                                                        </span>

                                                                    </div>
                                                                </div>


                                                                    <?php
                                                                    $buyableCount++;
                                                                    ?>
                                                            @endif
                                                        @endforeach
                                                    </div>



                                                    <div class="col-md-12 fe02" dir="rtl" id="featuresListIcons">
                                                        @if($buyableCount == 0)
                                                            <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
                                                        @endif
                                                        <br>
                                                    </div>

                                                    @else
                                                    <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
                                                @endif

                                            @else
                                                <h5 style="color: black;margin-top: 5px">موردی وجود ندارد</h5>
                                            @endif


                                            <div>

                                                <!-- CONTACT -->
                                                <div class="page-section" id="contact">
                                                    <div id="MeysamTemp" style="margin-top: 20px">
                                                        <div>
                                                            <fieldset style="padding-top: 30px" id="contact2">
                                                                <h4 class="widget-title"
                                                                    style="color: black;margin-top: 5px">رزرواسیون</h4>


                                                                <div class="row">
                                                                    <div class="col-md-12" dir="rtl" id="pictures">

                                                                        <div class="col-md-6" style="float: right;"
                                                                             id="persInfo_0">
                                                                            <fieldset>
                                                                                <legend>اطلاعات رزرو کننده:</legend>
                                                                                <div class="form-group">
                                                                                    <label class="required"
                                                                                           for="name_and_family">    {{trans('messages.lblNameAndFamily')}}

                                                                                    </label>
                                                                                    <div>
                                                                                        <input id="name_and_family_0"
                                                                                               type="text"
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
                                                                                        <input id="national_code_0"
                                                                                               type="text"
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

                                                                                        <input id="start_date_show_0"
                                                                                               type="text"
                                                                                               class="initial-value-example"
                                                                                               required
                                                                                               title=" تاریخ تولد را مشخص کنید"
                                                                                               autocomplete="off">
                                                                                        <input id="birth_date_0"
                                                                                               name="birth_date_0"
                                                                                               style="display: none">
                                                                                    </div>

                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="email_0">    {{trans('messages.lblEmail')}}
                                                                                        (اختیاری)</label>

                                                                                    <div>
                                                                                        <input id="email_0" type="text"
                                                                                               name="email_0"
                                                                                               value="{{ old('email') }}">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label for="personal_description">    موارد خاص
                                                                                        (اختیاری)</label>

                                                                                    <div>
                                                                                        <textarea id="personal_description_0" name="personal_description_0" placeholder="بیماری های خاص، دیابت، حساسیت، فشار خون و ..."></textarea>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row" style="height: 55px;"
                                                                                     dir="rtl">

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
                                                                                id="button1" type="button"
                                                                                onclick="Add();"
                                                                                title="جدید">
                                                                            <i class="fa fa-plus"
                                                                               style="font-size:40px;color:green"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="row form-group">
                                                                    <div class="col-md-12" style="margin-top: 20px;">
                                                                        <div class="col-md-12 form-group"
                                                                             id="captchSection">
                                                                            <p style="text-align: center">
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
                                                                        <div>


                                                                            @if(!$is_ended && ($tour->cost > 0))
                                                                                <div class="row" id="test"
                                                                                     style="alignment: center;margin-top: 10px;margin-bottom: 10px">
                                                                                    <div class="col-md-12"
                                                                                         style="text-align: center;font-size: large">
                                                                                        <span style="margin-left: 5px">  قیمت نهایی:</span>
                                                                                        <span id="final_cost"> {{$tour->cost}}</span>

                                                                                        {{--<span>تومان</span>--}}
                                                                                    </div>
                                                                                </div>


                                                                                <button type="submit"
                                                                                        class="btn btn-primary"
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
                                                                                            <span id="free" style="margin-left: 5px">رایگان</span>
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

                                                                            <br>
                                                                            <a href="{{url('/')}}"
                                                                               class="btn btn-primary"
                                                                               style="max-width:30%;display: block;margin-left: auto;margin-right: auto">  {{trans('messages.btnBack')}} </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>

                                                            <script>
                                                                // document.getElementById("final_cost").innerHTML = calculateCost();


                                                                var person_count = 1;

                                                                function Add() {
                                                                    person_count = person_count + 1;
                                                                    calculateCost();
                                                                    // if (document.getElementById("final_cost"))
                                                                    //     document.getElementById("final_cost").innerHTML = calculateCost();

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
                                                                    personal_description_name = "personal_description_" + count;


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

                                                                        "<div class='form-group'>" +
                                                                        "<label for='"+personal_description_name+"'>موارد خاص(اختیاری)</label>" +
                                                                        "<div>" +
                                                                        "<textarea id='"+personal_description_name+"' name='"+personal_description_name+"' placeholder=\"بیماری های خاص، دیابت، حساسیت، فشار خون و ...\"'>" +
                                                                        "</textarea>" +
                                                                        "</div>" +
                                                                        "</div>"+

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
                                                                    // document.getElementById("final_cost").innerHTML = calculateCost();

                                                                }

                                                                function Remove() {
                                                                    person_count = person_count - 1;
                                                                    // if (document.getElementById("final_cost"))
                                                                    //     document.getElementById("final_cost").innerHTML = calculateCost();
                                                                    calculateCost();

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

                    var inNumericIncreased = true;
                    var lastFeatureUid;
                    var lastFeatureCost;

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
                    var title = "<?php echo $tour->tour_address->address; ?>";


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

                    {{--function refreshCaptcha() {--}}

                        {{--// alert('ddd');--}}

                        {{--$.ajax({--}}
                            {{--url: '{{url("/refreshCaptcha")}}',--}}
                            {{--type: 'get',--}}
                            {{--dataType: 'html',--}}
                            {{--success: function (json) {--}}
                                {{--// alert(json);--}}
                                {{--$('.refereshrecapcha').html(json);--}}
                            {{--},--}}
                            {{--error: function (data) {--}}
                                {{--// alert('Try Again.');--}}
                                {{--swal({--}}
                                    {{--position: 'center',--}}
                                    {{--type: 'error',--}}
                                {{--})--}}
                            {{--}--}}
                        {{--});--}}
                    {{--}--}}

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

                    function calculateCost()
                    {
                        var argumentedFeaturesCost = 0;
                        var totalBuyables = 0;
                        if(document.getElementById("buyableFeatrues"))
                            totalBuyables = document.getElementById("buyableFeatrues").childElementCount;
                        // alert(totalBuyables);
                        for (var i = 0; i < totalBuyables; i++)
                        {
                            var featureCost = document.getElementById("buyable_cost_"+i).value;
                            var featureUid = document.getElementById("buyable_"+i).value;

                            var isChecked = document.getElementById("chk_" + featureUid).checked;
                            if (isChecked) {
                                argumentedFeaturesCost += (parseInt(document.getElementById("quantity_" + featureUid).value) * parseInt(featureCost));
                            }

                        }

                        var base_cost = "<?php echo($tour->cost) ?>";
                        total_cost = argumentedFeaturesCost + (person_count * base_cost);
                        if (document.getElementById("final_cost") && (total_cost > 0))
                        {
                            document.getElementById("final_cost").innerHTML = total_cost + " تومان ";
                            if(document.getElementById("free"))
                                document.getElementById("free").innerHTML = "";
                        }
                        else
                        {
                            if(document.getElementById("free"))
                                document.getElementById("free").innerHTML = "رایگان";
                            document.getElementById("final_cost").innerHTML = "";
                        }


                        if(total_cost > 0)
                            return (total_cost);
                        else
                            return "";
                    }

                    if (document.getElementById("final_cost"))
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

                </script>
@stop