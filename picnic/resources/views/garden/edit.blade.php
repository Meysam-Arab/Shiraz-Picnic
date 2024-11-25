@extends('layouts.master_garden')
@section('title',  trans('messages.tltGardenEdit'))

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

    ?>
    <script>
        var lastMonth = '';
    </script>
    <style>
        /*.col-4 {*/
        /*!*-webkit-box-flex: 1;*!*/
        /*-ms-flex: 0 0 33.333333%;*/
        /*flex: 0 0 33.333333%;*/
        /*!*flex-basis: 0;*!*/
        /*max-width: 33.333333%;*/
        /*width: 33.333333%;*/
        /*float: right;*/
        /*}*/

        #featuresListIcons .col-sm-4 {
            margin-bottom: 5px;
            /*float: right;*/
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

        .features {
            color: #555;
        }

        label.required:after {
            content: " *";
            color: red;
        }

        /* Popup container - can be anything you want */
        .popup {
            position: relative;
            display: inline-block;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* The actual popup */
        .popup .popuptext {
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
        .popup .popuptext::after {
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
        .popup .show {
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
                    src="{{URL::to('gardens/getFile/'.$garden->garden_id.'/'.$garden->garden_guid.'/1/2.22')}}" alt=""
                    style="width: 100%;height: auto"></div>
        <div class="welcome-text">
            <h2>{!! $garden->name !!}</h2>
            <h5>{!! $garden->address !!}</h5>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="fluid-container">

            <div class="content-wrapper">
                <!-- Address -->
                <div class="page-section" id="address">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="widget-title">آدرس</h4>
                            <div class="col-md-6">
                                <div class="about-image">
                                    <div id="map"
                                         style="width:466px;height:262px;display: block;margin-right: auto;margin-left: auto">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label>    {{trans('messages.lblType')}}</label>:

                                    <div>
                                        {!! \App\Garden::getTypeStringByCode($garden->type) !!}
                                    </div>
                                </div>
                                <br>
                                <div>
                                    <label> آدرس</label>:

                                    <div>
                                        {!! $garden->address !!}
                                    </div>
                                </div>
                                <br>
                                <div>
                                    <label>{{trans('messages.lblSurfaceArea')}}</label>:

                                    <div>
                                        {!! $features->surface_area !!} {{trans('messages.lblSquireMeter')}}
                                    </div>
                                </div>
                                <br>
                                <div>
                                    <label>    {{trans('messages.lblPeriodicHolidays')}}</label>:

                                    <div>
                                        @foreach($periodic_holidays as $periodic_holiday)
                                            <div>
                                                {!! \App\Utilities\Utility::getPersianDayOfWeekStringByCode($periodic_holiday->day_of_week) !!}
                                                <br>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{--<div>--}}
                                {{--<div>--}}
                                {{--{{trans('messages.lblLikeCount')}}: {!! $garden->like_count !!}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<br>--}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                        </div>
                    </div>
                </div>

                <!-- ABOUT -->
                <div class="page-section" id="about">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="widget-title">گالری تصاویر</h4>
                            <div class="fotorama about-image" data-allowfullscreen="native" data-nav="thumbs">
                                @foreach($pictures as $picture)
                                    <img src="{{URL::to('gardens/getFile/'.$garden->garden_id.'/'.$garden->garden_guid.'/'.$picture->media_id.'/2.22')}}">
                                @endforeach
                            </div>
                            <p>
                                <label>    {{trans('messages.lblRegulation')}}</label>:

                            <div>
                                {!! $garden->regulation !!}
                            </div>
                            </p>
                            <hr>
                        </div>
                    </div> <!-- #about -->
                </div>

                <!-- PROJECTS -->
                <div class="page-section" id="projects" dir="rtl">
                    <div class="row" dir="rtl">
                        <div class="col-md-12" dir="rtl">
                            <h4 class="widget-title"> امکانات</h4>
                            <p></p>
                            <br>
                        </div>
                    </div>
                    <div class="row" dir="rtl">
                        <div class="col-md-12" id="featuresListIcons">
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/building-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px;">
                                {{--ساختمان--}}
                                @if($features->building && (strcmp($features->building_description,'ندارد')!=0))
                                    <span class="features">{{trans('messages.lblBuilding')}}
                                        <div class="popup" onmouseover="showDetail(1)" onmouseout="HideDetail(1)"><a
                                                    class="fa fa-question-circle" aria-hidden="true"
                                                    style="color: #6b63e4"></a>
                                            <span class="popuptext" id="myPopup1">
                                             <p>
                                                    {{trans('messages.lblElevator')}}:
                                                 @if($features->elevator)
                                                     {{trans('messages.lblHave')}}
                                                 @else
                                                     {{trans('messages.lblDontHave')}}
                                                 @endif
                                                 <br>
                                                 {{trans('messages.lblFloorCount')}}
                                                 : {!! $features->floor_count !!} {{trans('messages.lblAddad')}}
                                                 <br>
                                                 {{trans('messages.lblRoomCount')}}
                                                 : {!! $features->room_count !!} {{trans('messages.lblAddad')}}
                                              </p>
                                                @if(strcmp($features->wc_description,'ندارد')!=0)
                                                    <p></p>
                                                @endif
                                             </span>
                                         </div>
                                    </span>
                                @elseif($features->building && (strcmp($features->building_description,'ندارد')==0) )
                                    <span id="myPopup13" class="features">{{trans('messages.lblBuilding')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblBuilding')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/car-compact-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--پارکینگ--}}
                                @if(($features->garage_open || $features->garage_open) && (strcmp($features->garage_description,'ندارد')!=0))
                                    <span class="features">{{trans('messages.lblGarage')}}
                                        <div class="popup" onmouseover="showDetail(2)" onmouseout="HideDetail(2)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup2">
                                                    <p>{{trans('messages.lblGarageCovered')}}:
                                                        @if($features->garage_covered)
                                                            {{trans('messages.lblHave')}}
                                                        @else
                                                            {{trans('messages.lblDontHave')}}
                                                        @endif
                                                        <br>
                                                        {{trans('messages.lblGarageOpen')}}:
                                                        @if($features->garage_open)
                                                            {{trans('messages.lblHave')}}
                                                        @else
                                                            {{trans('messages.lblDontHave')}}
                                                        @endif
                                                        <br>
                                                        {{trans('messages.lblCapacity')}}
                                                        : {!! $features->garage_capacity !!} {{trans('messages.lblAddad')}}

                                                    </p>
                                                     {!! $features->garage_description !!} <p></p>

                                                 </span>
                                          </div>
                                    </span>

                                @elseif (($features->garage_open || $features->garage_open) && (strcmp($features->garage_description,'ندارد')==0))
                                    <span id="myPopup2" class="features">{{trans('messages.lblGarage')}}</span>

                                @else <span
                                        style="text-decoration: line-through;">{{trans('messages.lblGarage')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/arbor-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--الاچیق--}}
                                @if( $features->arbour && (strcmp($features->arbour_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblArbour')}}
                                        <div class="popup" onmouseover="showDetail(3)" onmouseout="HideDetail(3)"><a
                                                    class="fa fa-question-circle"
                                                    aria-hidden="true" style="color: #6b63e4"></a>
                                            <span class="popuptext" id="myPopup3">
                                             <p>
                                                {{trans('messages.lblCount')}}
                                                 : {!! $features->arbour_count !!} {{trans('messages.lblAddad')}}
                                                 <br>
                                              </p>
                                             <p>
                                                {!! $features->arbour_description !!}
                                             </p>
                                          </span>
                                        </div>
                                    </span>
                                @elseif($features->arbour && (strcmp($features->arbour_description,'ندارد')==0) )
                                    <span id="myPopup3" class="features">{{trans('messages.lblArbour')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblArbour')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/terrace-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                @if( $features->terrace && (strcmp($features->terrace_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblTerrace')}}
                                        <div class="popup" onmouseover="showDetail(4)" onmouseout="HideDetail(4)"><a
                                                    class="fa fa-question-circle"
                                                    aria-hidden="true" style="color: #6b63e4"></a>
                                             <span class="popuptext" id="myPopup4">
{{--                                     <p>{{trans('messages.lblHave')}}</p>--}}
                                                 @if(strcmp($features->terrace_description,'ندارد')!=0)
                                                     <p>
                                             {!! $features->terrace_description !!}
                                         </p>
                                                 @endif
                                 </span>
                                        </div>
                                    </span>
                                @elseif($features->terrace && (strcmp($features->terrace_description,'ندارد')==0) )
                                    <span id="myPopup4" class="features">{{trans('messages.lblTerrace')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblTerrace')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/bathtub-with-opened-shower-svgrepo-com.svg')}}" width="30px"
                                     alt="" style="vertical-align: middle;margin-left:10px">
                                @if( $features->bathroom && (strcmp($features->bathroom_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblBathroom')}}
                                        <div class="popup" onmouseover="showDetail(5)" onmouseout="HideDetail(5)"><a
                                                    class="fa fa-question-circle"
                                                    aria-hidden="true"
                                                    style="color: #6b63e4"></a>
                                 <span class="popuptext" id="myPopup5">
                                     <p>
                                        {{trans('messages.lblBathroomCount')}}
                                         : {!! $features->bathroom_count !!} {{trans('messages.lblAddad')}}
                                         <br>
                                     </p>
                                     <p>
                                         {!! $features->bathroom_description !!}
                                     </p>
                                 </span>
                                </div>
                            </span>
                                @elseif($features->bathroom && (strcmp($features->bathroom_description,'ندارد')==0) )
                                    <span id="myPopup5" class="features">{{trans('messages.lblBathroom')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblBathroom')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/toilet-restroom-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                @if( ($features->wc_western || $features->wc_persian) && (strcmp($features->wc_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblWC')}}
                                        <div class="popup" onmouseover="showDetail(6)" onmouseout="HideDetail(6)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup6">
                                                     <p>
                                                         {{trans('messages.lblWCWestern')}}:
                                                         @if($features->wc_western)
                                                             {{trans('messages.lblHave')}}
                                                         @else
                                                             {{trans('messages.lblDontHave')}}
                                                         @endif
                                                         <br>
                                                         {{trans('messages.lblWCPersian')}}:
                                                         @if($features->wc_persian)
                                                             {{trans('messages.lblHave')}}
                                                         @else{{trans('messages.lblDontHave')}}
                                                         @endif
                                                         <br>
                                                         {{trans('messages.lblCount')}}
                                                         : {!! $features->wc_count !!} {{trans('messages.lblAddad')}}
                                                         <br>
                                                         </p>
                                                     @if(strcmp($features->wc_description,'ندارد')!=0)
                                                         <p>
                                                                 {!! $features->wc_description !!}
                                                             </p>
                                                     @endif
                                                 </span>
                                        </div>
                                    </span>
                                @elseif(($features->wc_western || $features->wc_persian) && (strcmp($features->wc_description,'ندارد')==0) )
                                    <span id="myPopup6" class="features">{{trans('messages.lblWC')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblWC')}}
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/swimming-pool-solid.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                @if( ($features->pool_covered || $features->pool_open)&& (strcmp($features->pool_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblPool')}}
                                        <div class="popup" onmouseover="showDetail(7)" onmouseout="HideDetail(7)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup7">
                                                       <p>
                                                 {{trans('messages.lblPoolCovered')}}:
                                                           @if($features->pool_covered)
                                                               {{trans('messages.lblHave')}}
                                                           @else{{trans('messages.lblDontHave')}}
                                                           @endif
                                                           <br>
                                                           {{trans('messages.lblPoolOpen')}}:
                                                           @if($features->pool_open)
                                                               {{trans('messages.lblHave')}}
                                                           @else
                                                               {{trans('messages.lblDontHave')}}
                                                           @endif
                                                           <br>
                                                         </p>
                                                  <p>
                                            {!! $features->pool_description !!}
                                                 </p>
                                                 </span>
                                           </div>
                                    </span>
                                    </span>
                                @elseif(($features->pool_covered || $features->pool_open) && (strcmp($features->pool_description,'ندارد')==0) )
                                    <span id="myPopup7" class="features">{{trans('messages.lblPool')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblPool')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/person-silhouette-in-sauna-svgrepo-com.svg')}}" width="30px"
                                     alt="" style="vertical-align: middle;margin-left:10px">
                                {{--<span>سونا</span>--}}
                                @if( $features->sauna && (strcmp($features->sauna_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblSauna')}}
                                        <div class="popup" onmouseover="showDetail(8)" onmouseout="HideDetail(8)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup8">
                                                          <p>
{{--                                            {{trans('messages.lblHave')}}--}}
                                        </p>
                                                     @if(strcmp($features->sauna_description,'ندارد')!=0)
                                                         <p>
                                                {!! $features->sauna_description !!}
                                            </p>
                                                     @endif
                                                 </span>
                                            </div>
                                        </span>
                                @elseif($features->sauna && (strcmp($features->sauna_description,'ندارد')==0) )
                                    <span id="myPopup8" class="features">{{trans('messages.lblSauna')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblSauna')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/person-enjoying-jacuzzi-hot-water-bath-svgrepo-com.svg')}}"
                                     width="30px" alt="" style="vertical-align: middle;margin-left:10px">
                                {{--<span>جکوزی</span>--}}
                                @if( $features->jacuzzi && (strcmp($features->jacuzzi_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblJacuzzi')}}
                                        <div class="popup" onmouseover="showDetail(9)" onmouseout="HideDetail(9)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup9">
                                                     <p>
{{--                                                         {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->jacuzzi_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->jacuzzi_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                         </div>
                                    </span>
                                @elseif($features->jacuzzi && (strcmp($features->jacuzzi_description,'ندارد')==0) )
                                    <span id="myPopup9" class="features">{{trans('messages.lblJacuzzi')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblJacuzzi')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/old-telephone-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>تلفن ثابت</span>--}}
                                @if( $features->tel && (strcmp($features->tel_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblTel')}}
                                        <div class="popup" onmouseover="showDetail(10)" onmouseout="HideDetail(10)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup10">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->tel_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->tel_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                      </div>
                                    </span>
                                @elseif($features->tel && (strcmp($features->tel_description,'ندارد')==0) )
                                    <span id="myPopup10" class="features"> {{trans('messages.lblTel')}}</span>
                                @else
                                    <span style="text-decoration: line-through;"> {{trans('messages.lblTel')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/plumbing-pipe-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                @if( $features->plumbing && (strcmp($features->plumbing_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblPlumbing')}}
                                        <div class="popup" onmouseover="showDetail(11)" onmouseout="HideDetail(11)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup11">
                                                     <p></p>
                                                     @if(strcmp($features->plumbing_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->plumbing_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                      </div>
                                    </span>
                                @elseif($features->plumbing && (strcmp($features->plumbing_description,'ندارد')==0) )
                                    <span id="myPopup11" class="features">   {{trans('messages.lblPlumbing')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblPlumbing')}}   </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/electricity-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>برق</span>--}}
                                @if( $features->electricity && (strcmp($features->electricity_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblElectricity')}}
                                        <div class="popup" onmouseover="showDetail(12)" onmouseout="HideDetail(12)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup12">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->electricity_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->electricity_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                             </div>
                                    </span>
                                @elseif($features->electricity && (strcmp($features->electricity_description,'ندارد')==0) )
                                    <span id="myPopup12" class="features">  {{trans('messages.lblElectricity')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblElectricity')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/lighting-bulb-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>روشنایی</span>--}}
                                @if( $features->lighting && (strcmp($features->lighting_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblLighting')}}
                                        <div class="popup" onmouseover="showDetail(13)" onmouseout="HideDetail(13)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup13">
                                                     <p>{!! $features->lighting_description !!}</p>

                                                 </span>
                                               </div>
                                    </span>
                                @elseif($features->lighting && (strcmp($features->lighting_description,'ندارد')==0) )
                                    <span id="myPopup13" class="features">{{trans('messages.lblLighting')}}</span>
                                @else
                                    <span id="myPopup13"
                                          style="text-decoration: line-through;">{{trans('messages.lblLighting')}}</span>
                                @endif
                            </div>

                            <div class="col-sm-4">

                                <img src="{{url('/SVGs/cooling-fan-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>سیستم سرمایشی</span>--}}
                                @if( $features->cooling_system && (strcmp($features->cooling_system_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblCoolingSystem')}}
                                        <div class="popup" onmouseover="showDetail(14)" onmouseout="HideDetail(14)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup14">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->cooling_system_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->cooling_system_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                        </div>
                                    </span>
                                @elseif($features->cooling_system && (strcmp($features->cooling_system_description,'ندارد')==0) )
                                    <span id="myPopup14"
                                          class="features">  {{trans('messages.lblCoolingSystem')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">  {{trans('messages.lblCoolingSystem')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/heating-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>سیستم گرمایشی</span>--}}
                                @if( $features->heating_system && (strcmp($features->heating_system_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblHeatingSystem')}}
                                        <div class="popup" onmouseover="showDetail(15)" onmouseout="HideDetail(15)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup15">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->heating_system_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->heating_system_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                    </div>
                                    </span>
                                @elseif($features->heating_system && (strcmp($features->heating_system_description,'ندارد')==0) )
                                    <span id="myPopup15"
                                          class="features">  {{trans('messages.lblHeatingSystem')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">  {{trans('messages.lblHeatingSystem')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/sport-centre-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>وسایل ورزشی</span>--}}
                                @if( $features->sports_equipment && (strcmp($features->sports_equipment_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblSportsEquipment')}}
                                        <div class="popup" onmouseover="showDetail(16)" onmouseout="HideDetail(16)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup16">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->sports_equipment_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->sports_equipment_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                        </div>
                                    </span>
                                @elseif($features->sports_equipment && (strcmp($features->sports_equipment_description,'ندارد')==0) )
                                    <span id="myPopup16"
                                          class="features">   {{trans('messages.lblSportsEquipment')}}</span>
                                @else
                                    <span style="text-decoration: line-through;"> {{trans('messages.lblSportsEquipment')}}

                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/eight-ball-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>میز بیلیارد</span>--}}
                                @if( $features->pool_table && (strcmp($features->pool_table_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblPoolTable')}}
                                        <div class="popup" onmouseover="showDetail(17)" onmouseout="HideDetail(17)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup17">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->pool_table_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->pool_table_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                                 </div>
                                    </span>
                                @elseif($features->pool_table && (strcmp($features->pool_table_description,'ندارد')==0) )
                                    <span id="myPopup17" class="features">     {{trans('messages.lblPoolTable')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">    {{trans('messages.lblPoolTable')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/volleyball-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>زمین والیبال</span>--}}
                                @if( $features->volleyball_field && (strcmp($features->volleyball_field_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblVolleyballField')}}
                                        <div class="popup" onmouseover="showDetail(18)" onmouseout="HideDetail(18)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup18">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->volleyball_field_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->volleyball_field_description !!}
                                                     </p>
                                                     @endif

                                                 </span>
                                    </div>

                                    </span>
                                @elseif($features->volleyball_field && (strcmp($features->volleyball_field_description,'ندارد')==0) )
                                    <span id="myPopup18"
                                          class="features">     {{trans('messages.lblVolleyballField')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">      {{trans('messages.lblVolleyballField')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/sports-balls-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>زمین فوتبال</span>--}}
                                @if( $features->football_field && (strcmp($features->football_field_description,'ندارد')!=0) )
                                    <span class="features">  {{trans('messages.lblFootballField')}}

                                        <div class="popup" onmouseover="showDetail(19)" onmouseout="HideDetail(19)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup19">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->football_field_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->football_field_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                        </div>
                                    </span>
                                @elseif($features->football_field && (strcmp($features->football_field_description,'ندارد')==0) )
                                    <span id="myPopup19"
                                          class="features">    {{trans('messages.lblFootballField')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">     {{trans('messages.lblFootballField')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/tennis-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> زمین تنیس</span>--}}
                                @if( $features->tennis_field && (strcmp($features->tennis_field_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblTennisField')}}
                                        <div class="popup" onmouseover="showDetail(20)" onmouseout="HideDetail(20)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup20">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->tennis_field_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->tennis_field_description !!}
                                                     </p>
                                                     @endif

                                                 </span>
                                     </div>

                                    </span>
                                @elseif($features->tennis_field && (strcmp($features->tennis_field_description,'ندارد')==0) )
                                    <span id="myPopup20"
                                          class="features">    {{trans('messages.lblTennisField')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">     {{trans('messages.lblTennisField')}}</span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/basketball-match-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> زمین بسکتبال</span>--}}
                                @if( $features->basketball_field && (strcmp($features->basketball_field_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblBasketballField')}}
                                        <div class="popup" onmouseover="showDetail(21)" onmouseout="HideDetail(21)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup21">
                                                       <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->basketball_field_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->basketball_field_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                     </div>
                                    </span>
                                @elseif($features->basketball_field && (strcmp($features->basketball_field_description,'ندارد')==0) )
                                    <span id="myPopup21"
                                          class="features">    {{trans('messages.lblBasketballField')}}</span>
                                @else
                                    <span style="text-decoration: line-through;">     {{trans('messages.lblBasketballField')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">

                                <img src="{{url('/SVGs/soccer-player-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> فوتبال دستی</span>--}}
                                @if( $features->foosball && (strcmp($features->foosball_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblFoosball')}}
                                        <div class="popup" onmouseover="showDetail(22)" onmouseout="HideDetail(22)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup22">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->foosball_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->foosball_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                    </div>
                                    </span>
                                @elseif($features->foosball && (strcmp($features->foosball_description,'ندارد')==0) )
                                    <span id="myPopup22" class="features">     {{trans('messages.lblFoosball')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">     {{trans('messages.lblFoosball')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/ping-pong-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>پینگ پنگ</span>--}}
                                @if( $features->ping_pong && (strcmp($features->ping_pong_description,'ندارد')!=0) )
                                    <span class="features">  {{trans('messages.lblPingPong')}}
                                        <div class="popup" onmouseover="showDetail(23)" onmouseout="HideDetail(23)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup23">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->ping_pong_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->ping_pong_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                    </div>
                                    </span>
                                @elseif($features->ping_pong && (strcmp($features->ping_pong_description,'ندارد')==0) )
                                    <span id="myPopup23" class="features">  {{trans('messages.lblPingPong')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">     {{trans('messages.lblPingPong')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/barbecue-grill-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> باربیکیو</span>--}}
                                @if( $features->barbecue && (strcmp($features->barbecue_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblBarbecue')}}
                                        <div class="popup" onmouseover="showDetail(24)" onmouseout="HideDetail(24)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup24">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->barbecue_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->barbecue_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                        </div>

                                    </span>
                                @elseif($features->barbecue && (strcmp($features->barbecue_description,'ندارد')==0) )
                                    <span id="myPopup24" class="features">  {{trans('messages.lblBarbecue')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">    {{trans('messages.lblBarbecue')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/oven-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> اجاق گاز</span>--}}
                                @if( $features->oven && (strcmp($features->oven_description,'ندارد')!=0) )
                                    <span class="features">  {{trans('messages.lblOven')}}
                                        <div class="popup" onmouseover="showDetail(25)" onmouseout="HideDetail(25)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup25">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->oven_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->oven_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                      </div>
                                    </span>
                                @elseif($features->oven && (strcmp($features->oven_description,'ندارد')==0) )
                                    <span id="myPopup25" class="features">  {{trans('messages.lblOven')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">    {{trans('messages.lblOven')}}  </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/vintoge-microwave-oven-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>  ماکروفر</span>--}}
                                {{--                                @if($features->macro && $features->macro_description)--}}
                                @if( $features->macro && (strcmp($features->macro_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblMacro')}}
                                        <div class="popup" onmouseover="showDetail(26)" onmouseout="HideDetail(26)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup26">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->macro_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->macro_description !!}
                                                     </p>
                                                     @endif

                                                 </span>
                                     </div>
                                    </span>
                                @elseif($features->macro && (strcmp($features->macro_description,'ندارد')==0) )
                                    <span id="myPopup26" class="features">  {{trans('messages.lblMacro')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">    {{trans('messages.lblMacro')}} </span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/refrigerator-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> یخچال</span>--}}
                                @if( $features->refrigerator && (strcmp($features->refrigerator_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblRefrigerator')}}
                                        <div class="popup" onmouseover="showDetail(27)" onmouseout="HideDetail(27)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup27">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->refrigerator_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->refrigerator_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                    </div>
                                    </span>
                                @elseif($features->refrigerator && (strcmp($features->refrigerator_description,'ندارد')==0) )
                                    <span id="myPopup27"
                                          class="features">  {{trans('messages.lblRefrigerator')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">    {{trans('messages.lblRefrigerator')}} </span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/vacuum-cleaner-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> جارو برقی</span>--}}
                                @if( $features->vacuum_cleaner && (strcmp($features->vacuum_cleaner_description,'ندارد')!=0) )
                                    <span class="features">   {{trans('messages.lblVacuumCleaner')}}
                                        <div class="popup" onmouseover="showDetail(28)" onmouseout="HideDetail(28)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup28">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->vacuum_cleaner_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->vacuum_cleaner_description !!}
                                                     </p>
                                                     @endif

                                                 </span>
                                        </div>
                                    </span>
                                @elseif($features->vacuum_cleaner && (strcmp($features->vacuum_cleaner_description,'ندارد')==0) )
                                    <span id="myPopup28"
                                          class="features">   {{trans('messages.lblVacuumCleaner')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">  {{trans('messages.lblVacuumCleaner')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/iron-svgrepo-com (1).svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> اتو</span>--}}
                                @if( $features->otto && (strcmp($features->otto_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblOtto')}}
                                        <div class="popup" onmouseover="showDetail(29)" onmouseout="HideDetail(29)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup29">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->otto_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->otto_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                       </div>
                                    </span>
                                @elseif($features->otto && (strcmp($features->otto_description,'ندارد')==0) )
                                    <span id="myPopup29" class="features">   {{trans('messages.lblOtto')}} </span>
                                @else
                                    <span style="text-decoration: line-through;"> {{trans('messages.lblOtto')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/video-player-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> گیرنده دیجیتال</span>--}}
                                @if( $features->digital_receiver && (strcmp($features->digital_receiver_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblDigitalReceiver')}}
                                        <div class="popup" onmouseover="showDetail(30)" onmouseout="HideDetail(30)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup30">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->digital_receiver_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->digital_receiver_description !!}
                                                     </p>
                                                     @endif

                                                 </span>
                                     </div>
                                    </span>
                                @elseif($features->digital_receiver && (strcmp($features->digital_receiver_description,'ندارد')==0) )
                                    <span id="myPopup30"
                                          class="features">  {{trans('messages.lblDigitalReceiver')}} </span>
                                @else
                                    <span style="text-decoration: line-through;"> {{trans('messages.lblDigitalReceiver')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/closet-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> کمد</span>--}}
                                @if( $features->closet && (strcmp($features->closet_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblCloset')}}
                                        <div class="popup" onmouseover="showDetail(31)" onmouseout="HideDetail(31)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup31">
                                                      <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->closet_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->closet_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                     </div>
                                    </span>
                                @elseif($features->closet && (strcmp($features->closet_description,'ندارد')==0) )
                                    <span id="myPopup31" class="features">  {{trans('messages.lblCloset')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">    {{trans('messages.lblCloset')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/chest-of-drawers-furniture-svgrepo-com.svg')}}" width="30px"
                                     alt="" style="vertical-align: middle;margin-left:10px">
                                {{--<span> دراور</span>--}}
                                @if( $features->drawer && (strcmp($features->drawer_description,'ندارد')!=0) )
                                    <span class="features">  {{trans('messages.lblDrawer')}}
                                        <div class="popup" onmouseover="showDetail(32)" onmouseout="HideDetail(32)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup32">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->drawer_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->drawer_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                       </div>
                                    </span>
                                @elseif($features->drawer && (strcmp($features->drawer_description,'ندارد')==0) )
                                    <span id="myPopup32" class="features">  {{trans('messages.lblDrawer')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">    {{trans('messages.lblDrawer')}} </span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/dining-table-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>  میز نهار خوری</span>--}}
                                @if( $features->dining_table && (strcmp($features->dining_table_description,'ندارد')!=0) )
                                    <span class="features">   {{trans('messages.lblDiningTable')}}
                                        <div class="popup" onmouseover="showDetail(33)" onmouseout="HideDetail(33)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup33">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->dining_table_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->dining_table_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                        </div>
                                    </span>
                                @elseif($features->dining_table && (strcmp($features->dining_table_description,'ندارد')==0) )
                                    <span id="myPopup33"
                                          class="features">     {{trans('messages.lblDiningTable')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">      {{trans('messages.lblDiningTable')}} </span>
                                @endif

                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/armchair-furniture-and-household-svgrepo-com.svg')}}"
                                     width="30px" alt="" style="vertical-align: middle;margin-left:10px">
                                {{--<span>  مبلمان</span>--}}
                                @if( $features->furniture && (strcmp($features->furniture_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblFurniture')}}
                                        <div class="popup" onmouseover="showDetail(34)" onmouseout="HideDetail(34)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup34">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->furniture_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->furniture_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                    </div>
                                    </span>
                                @elseif($features->furniture && (strcmp($features->furniture_description,'ندارد')==0) )
                                    <span id="myPopup34"
                                          class="features">     {{trans('messages.lblFurniture')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">    {{trans('messages.lblFurniture')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">

                                <img src="{{url('/SVGs/television-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>  تلوزیون</span>--}}
                                @if( $features->television && (strcmp($features->television_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblTelevision')}}
                                        <div class="popup" onmouseover="showDetail(35)" onmouseout="HideDetail(35)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup35">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->television_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->television_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                       </div>
                                    </span>
                                @elseif($features->television && (strcmp($features->television_description,'ندارد')==0) )
                                    <span id="myPopup35"
                                          class="features">     {{trans('messages.lblTelevision')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">     {{trans('messages.lblTelevision')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/washing-machine-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> ماشین لباسشویی</span>--}}
                                @if( $features->washing_machine && (strcmp($features->washing_machine_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblWashingMachine')}}
                                        <div class="popup" onmouseover="showDetail(36)" onmouseout="HideDetail(36)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup36">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->washing_machine_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->washing_machine_description !!}
                                                     </p>
                                                     @endif

                                                 </span>
                                       </div>
                                    </span>
                                @elseif($features->washing_machine && (strcmp($features->washing_machine_description,'ندارد')==0) )
                                    <span id="myPopup36"
                                          class="features">      {{trans('messages.lblWashingMachine')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">  {{trans('messages.lblWashingMachine')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/hairdryer-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span> سشوار</span>--}}
                                @if( $features->hairdryer && (strcmp($features->hairdryer_description,'ندارد')!=0) )
                                    <span class="features">  {{trans('messages.lblHairdryer')}}
                                        <div class="popup" onmouseover="showDetail(37)" onmouseout="HideDetail(37)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup37">
                                                     <p>
{{--                                                     {{trans('messages.lblHave')}}--}}
                                                     </p>
                                                     @if(strcmp($features->hairdryer_description,'ندارد')!=0)
                                                         <p>
                                                     {!! $features->hairdryer_description !!}
                                                     </p>
                                                     @endif
                                                 </span>
                                     </div>
                                    </span>
                                @elseif($features->hairdryer && (strcmp($features->hairdryer_description,'ندارد')==0) )
                                    <span id="myPopup37"
                                          class="features">      {{trans('messages.lblHairdryer')}} </span>
                                @else
                                    <span style="text-decoration: line-through;"> {{trans('messages.lblHairdryer')}} </span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/electrical-worker-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>  سرایدار</span>--}}
                                @if( $features->janitor && (strcmp($features->janitor_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblJanitor')}}
                                        <div class="popup" onmouseover="showDetail(38)" onmouseout="HideDetail(38)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup38">
                                                               @if(strcmp($features->janitor_outside,'ندارد')!=0)
                                                         <p>
                                                     خارج
                                                     </p>
                                                     @else
                                                         <p>داخل</p>
                                                     @endif
                                                 </span>
                                    </div>
                                    </span>
                                @elseif($features->janitor && (strcmp($features->janitor_description,'ندارد')==0) )
                                    <span id="myPopup38" class="features">      {{trans('messages.lblJanitor')}} </span>
                                @else
                                    <span style="text-decoration: line-through;">{{trans('messages.lblJanitor')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <img src="{{url('/SVGs/policeman-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>  نگهبان</span>--}}
                                @if( $features->guard && (strcmp($features->guard_description,'ندارد')!=0) )
                                    <span class="features"> {{trans('messages.lblGuard')}}
                                        <div class="popup" onmouseover="showDetail(39)" onmouseout="HideDetail(39)">
                                            <a class="fa fa-question-circle" aria-hidden="true"
                                               style="color: #6b63e4"></a>
                                                 <span class="popuptext" id="myPopup39">

                                                     @if(strcmp($features->guard_outside,'ندارد')!=0)
                                                         <p>
                                                     خارج
                                                     </p>
                                                     @else
                                                         <p>داخل</p>
                                                     @endif
                                                 </span>
                                       </div>
                                    </span>
                                @elseif($features->guard && (strcmp($features->guard_description,'ندارد')==0) )
                                    <span id="myPopup39" class="features">      {{trans('messages.lblGuard')}} </span>
                                @else
                                    <span style="text-decoration: line-through;"> {{trans('messages.lblGuard')}}</span>
                                @endif
                            </div>
                            <div class="col-sm-4">

                                <img src="{{url('/SVGs/wifi-sign-svgrepo-com.svg')}}" width="30px" alt=""
                                     style="vertical-align: middle;margin-left:10px">
                                {{--<span>وای فای</span>--}}
                                @if( $features->wifi && (strcmp($features->wifi_description,'ندارد')!=0) )
                                    <span class="features">{{trans('messages.lblWiFi')}}
                                        <div class="popup" onmouseover="showDetail(40)" onmouseout="HideDetail(40)">
                                <a class="fa fa-question-circle" aria-hidden="true" style="color: #6b63e4"></a>
                                <span class="popuptext" id="myPopup40">
                                <p>{!! $features->wifi_description !!}</p>

                                </span>
                                </div>
                                </span>


                                @elseif($features->wifi && (strcmp($features->wifi_description,'ندارد')==0) )
                                    <span id="myPopup40" class="features">{{trans('messages.lblWiFi')}} </span>
                                @else
                                    <span style="text-decoration: line-through;"> {{trans('messages.lblWiFi')}}</span>
                                @endif
                            </div>

                        </div>
                        {{--// end of div 12--}}
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>

                <!-- CONTACT -->
                <div class="page-section" id="contact">

                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="widget-title">ثبت رزرو</h4>
                            <p> در تقویم زیر، جدول برنامه ی باغ و همچنین قیمت برای رزور هرروز را مشاهده خواهید کرد.</p>
                        </div>
                    </div>
                    <div class="row" id="bigCalender">
                        <div class="col-md-12">
                            <input id="inlineExampleAlt" class="datepicker-demo" style="display: none"/>
                            <div class="inline-example"></div>
                            <script>
                                $('.inline-example').persianDatepicker({
                                    inline: true,
                                    minDate: new persianDate().unix(),
                                    altField: '#inlineExampleAlt',
                                    altFormat: 'LLLL',
                                    toolbox: {
                                        calendarSwitch: {
                                            enabled: false
                                        },
                                        todayButton: {
                                            onToday: function () {
                                                ArrangeCalender();
                                            },
                                        }
                                    },
                                    navigator: {
                                        scroll: {
                                            enabled: false
                                        },
                                        onNext: function () {
                                            ArrangeCalender();
                                            lastMonth = document.getElementsByClassName("pwt-btn-switch")[0].innerText;
                                        },
                                        onPrev: function () {
                                            ArrangeCalender();
                                            lastMonth = document.getElementsByClassName("pwt-btn-switch")[0].innerText;
                                        },
                                        onSelect: function () {
                                            ArrangeCalender();
                                        }
                                    },
                                    monthPicker: {
                                        onSelect: function () {
                                            ArrangeCalender();
                                            lastMonth = document.getElementsByClassName("pwt-btn-switch")[0].innerText;
                                        }
                                    },
                                    dayPicker: {
                                        onSelect: function () {
                                            if (isMonthChanged())
                                                ArrangeCalender();
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>


                    <div id="MeysamTemp" style="margin-top: 20px">
                        <form name="reserve_form" role="form" method="POST"
                              action="{{ url('/transactions/storeGarden') }}">
                            {{ csrf_field() }}
                            <input type="hidden" id="garden_id" name="garden_id" value="{{$garden->garden_id}}">
                            <div class="row">
                                <fieldset>
                                    <div class="col-md-12">
                                        <div class="col-md-7">
                                            <fieldset>
                                                <legend>زمان رزرو:</legend>

                                                <label for="selectbox">نوع رزرو:</label>
                                                <select id="reserved_kind" name="reserved_kind" data-selected="">
                                                    <option value="1">یک روز یا کمتر</option>
                                                    <option value="2">چند روز</option>
                                                </select>
                                                <script>
                                                    var $reserved_kind = $("#reserved_kind");
                                                    $reserved_kind.on("change", changeImageSelection);

                                                    function changeImageSelection() {
                                                        switch ($reserved_kind.val()) {
                                                            case "1":
                                                                $('#form_ctr_01').css("display", "none");
                                                                $('#form_ctr_02').css("display", "block");
                                                                $('#form_ctr_03').css("display", "none");
                                                                $('#form_ctr_04').css("display", "none");
                                                                $('#form_ctr_05').css("display", "none");
                                                                break;
                                                            // case "2":
                                                            //     $('#form_ctr_01').css("display", "none");
                                                            //     $('#form_ctr_02').css("display", "block");
                                                            //     $('#form_ctr_03').css("display", "none");
                                                            //     $('#form_ctr_04').css("display", "none");
                                                            //     $('#form_ctr_05').css("display", "none");
                                                            //     break;
                                                            default:
                                                                $('#form_ctr_01').css("display", "none");
                                                                $('#form_ctr_02').css("display", "block");
                                                                $('#form_ctr_03').css("display", "block");
                                                                $('#form_ctr_04').css("display", "none");
                                                                $('#form_ctr_05').css("display", "block");
                                                        }
                                                    }
                                                </script>

                                                <div class="col-md-12">

                                                    <div class="form-group">
                                                        <label for="start_date" class="required"
                                                               class="required">    {{trans('messages.lblStartDate')}}</label>

                                                        <div>
                                                            <input id="start_date_show" type="text"
                                                                   value="" class="initial-value-example"
                                                                   required title="تاریخ شروع">
                                                            <input id="start_date" name="start_date"
                                                                   style="display: none">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="form_ctr_01" style="display: none">
                                                        <label for="start_week_day_id">{{trans('messages.lblReserveInfo')}}</label>
                                                        <?php
                                                        $holydays = array();
                                                        foreach ($periodic_holidays as $periodic_holiday) {
                                                            array_push($holydays, $periodic_holiday->day_of_week);
                                                        }
                                                        ?>
                                                        <select id="start_week_day_id" name="start_week_day_id"
                                                                onChange="doReloadShift('start_week_day_id','shift_id_1');">
                                                            <option value="-1"> {{trans('messages.txtChoose')}}</option>
                                                            @if(in_array("0",$holydays) == false)
                                                                <option value="0"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(0)}}</option>
                                                            @endif
                                                            @if(in_array("1",$holydays) == false)
                                                                <option value="1"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(1)}}</option>
                                                            @endif
                                                            @if(in_array("2",$holydays) == false)
                                                                <option value="2"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(2)}}</option>
                                                            @endif
                                                            @if(in_array("3",$holydays) == false)
                                                                <option value="3"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(3)}}</option>
                                                            @endif
                                                            @if(in_array("4",$holydays) == false)
                                                                <option value="4"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(4)}}</option>
                                                            @endif
                                                            @if(in_array("5",$holydays) == false)
                                                                <option value="5"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(5)}}</option>
                                                            @endif
                                                            @if(in_array("6",$holydays) == false)
                                                                <option value="6"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(6)}}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group" id="form_ctr_02">
                                                        <label class="required" for="shift_id_1"
                                                               style="display: block">{{trans('messages.lblShifts')}}</label>
                                                        <select id="shift_id_1" name="shift_id_1" required>
                                                            <option value="-1"> {{trans('messages.txtChoose')}}</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group" id="form_ctr_03" style="display: none">
                                                        <label for="end_date" class="required"
                                                               title="تاریخ پایان">    {{trans('messages.lblEndDate')}}</label>
                                                        <div>
                                                            <input id="end_date_show" type="text"
                                                                   value=""
                                                            >
                                                            <input id="end_date" name="end_date" style="display: none">
                                                        </div>
                                                    </div>

                                                    <div class="form-group" id="form_ctr_04" style="display: none">
                                                        <label for="end_week_day_id">{{trans('messages.lblReserveInfo')}}</label>
                                                        <?php
                                                        $holydays = array();
                                                        foreach ($periodic_holidays as $periodic_holiday) {
                                                            array_push($holydays, $periodic_holiday->day_of_week);
                                                        }
                                                        ?>
                                                        <select id="end_week_day_id" name="end_week_day_id"
                                                                onChange="doReloadShift('end_week_day_id','shift_id_2');">
                                                            <option value="-1"> {{trans('messages.txtChoose')}}</option>
                                                            @if(in_array("0",$holydays) == false)
                                                                <option value="0"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(0)}}</option>
                                                            @endif
                                                            @if(in_array("1",$holydays) == false)
                                                                <option value="1"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(1)}}</option>
                                                            @endif
                                                            @if(in_array("2",$holydays) == false)
                                                                <option value="2"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(2)}}</option>
                                                            @endif
                                                            @if(in_array("3",$holydays) == false)
                                                                <option value="3"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(3)}}</option>
                                                            @endif
                                                            @if(in_array("4",$holydays) == false)
                                                                <option value="4"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(4)}}</option>
                                                            @endif
                                                            @if(in_array("5",$holydays) == false)
                                                                <option value="5"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(5)}}</option>
                                                            @endif
                                                            @if(in_array("6",$holydays) == false)
                                                                <option value="6"> {{\App\Utilities\Utility::getPersianDayOfWeekStringByCode(6)}}</option>
                                                            @endif
                                                        </select>
                                                    </div>

                                                    <div class="form-group" id="form_ctr_05" style="display: none">
                                                        <label for="shift_id_2"
                                                               style="display: block">{{trans('messages.lblShifts')}}</label>
                                                        <select id="shift_id_2" name="shift_id_2"
                                                                ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~name="shift_id_2">
                                                            <option value="-1"> {{trans('messages.txtChoose')}}</option>
                                                        </select>
                                                    </div>


                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-5">
                                            <fieldset>
                                                <legend>اطلاعات رزرو کننده:</legend>
                                                <div class="form-group">
                                                    <label class="required"
                                                           for="name_and_family">    {{trans('messages.lblNameAndFamily')}}
                                                        {{--<sup class="star">*</sup>--}}
                                                    </label>

                                                    <div>
                                                        <input id="name_and_family" type="text" name="name_and_family"
                                                               title="نام و نام خانوادگی را وارد کنید.  "
                                                               value="{{ old('name_and_family') }}"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="required"
                                                           for="national_code">    {{trans('messages.lblNationalCode')}}</label>
                                                    <div>
                                                        <input id="national_code" type="text" name="national_code"
                                                               title="کد ملی را وارد کنید."
                                                               value="{{ old('national_code') }}"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="required"
                                                           for="mobile">    {{trans('messages.lblMobile')}}</label>

                                                    <div>
                                                        <input id="mobile" type="text" name="mobile"
                                                               title="شماره تلفن همراه را وارد کنید."
                                                               value="{{ old('mobile') }}"
                                                               required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">    {{trans('messages.lblEmail')}}
                                                        (اختیاری)</label>

                                                    <div>
                                                        <input id="email" type="text" name="email"
                                                               value="{{ old('email') }}">
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <div class="col-md-12 form-group" id="captchSection">
                                                <p style="text-align: center">
                                                    با کلیک روی کادر انتخابی زیر شما تایید می کنید که ربات نیستید و
                                                    همچنین <a target="_blank" href="{{url('/termsOfUse') }}"> شرایط و
                                                        قوانین </a> استفاده از سایت را پذیرفته اید.
                                                </p>
                                                <div class="form-group refereshrecapcha">
                                                {!! captcha_img('flat') !!}
                                                </div>
                                                <button class="btn" onclick="refreshCaptcha()">{{trans('messages.btnRefresh')}}</button>
                                                <br>
                                                {{trans('messages.lblCaptcha')}}:<input type="text" name="captcha" id="captcha">

                                                {{--<script src="https://authedmine.com/lib/captcha.min.js" async></script>--}}
                                                {{--<div id="sd" data-callback="myCaptchaCallback"--}}
                                                     {{--data-disable-elements="button[type=submit]"--}}
                                                     {{--data-whitelabel="false" class="coinhive-captcha" data-hashes="256"--}}
                                                     {{--data-key="SlWFDeLelUF8Fyc9i5wVhECIBuzzZWeH">--}}
                                                    {{--<em>{{trans('messages.msgCaptchaLoading')}}<br>--}}
                                                        {{--{{trans('messages.msgCaptchaLoadingWait')}}</em>--}}
                                                {{--</div>--}}
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <div class="alert alert-warning">
                                                    فردی که مشخصات او در این فرم به عنوان رزرو کننده ثبت شده بایستی برای
                                                    ورود به باغ حضور داشته باشد و کارت ملی خود را نیز به همراه داشته
                                                    باشد.
                                                </div>
                                                <div class="alert alert-danger">
                                                    با فشردن دکمه ثبت و پرداخت شما متعهد به رعایت همه ی قوانین اخلاقی و
                                                    شرعی و عرفی جمهوری اسلامی ایران خواهید بود و مسئولیت هرگونه مشکلی که
                                                    در اثر رعایت نکردن این قوانین به وجود بیاید به عهده خود شما می باشد.
                                                </div>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-primary"
                                                        style="min-width:30%;display: block;margin-left: auto;margin-right: auto;">
                                                    ثبت و پرداخت
                                                </button>
                                                <br>
                                                <a href="{{url()->previous() }}" class="btn btn-primary"
                                                   style="max-width:30%;display: block;margin-left: auto;margin-right: auto">  {{trans('messages.btnBack')}} </a>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>

                <div class="row" id="footer">
                    <div class="col-md-12 text-center">
                        <p class="copyright-text">تمامی حقوق محفوظ است</p>
                    </div>

                </div>

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
        var maxDayShamsi = "<?php echo $reserve_day_limit; ?>";
        var maxDayShamsi2 = parseInt((maxDayShamsi.replace('-', '')).replace('-', ''));

        var on_time_holidays_string = '<?php echo json_encode($on_time_holidays); ?>';
        var on_time_holidays = JSON.parse(on_time_holidays_string);

        var reserved_dates_string = '<?php echo json_encode($reserved_dates); ?>';
        var reserved_dates = JSON.parse(reserved_dates_string);

        var periodic_holidays_string = '<?php echo json_encode($periodic_holidays); ?>';
        var periodic_holidays = JSON.parse(periodic_holidays_string);
        var ph = [];

        for (var i = 0; i < periodic_holidays.length; i++) {
            ph.push(parseInt(periodic_holidays[i].day_of_week));
        }

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

        function containsObject_second(obj, list) {
            var i;
            var flag = false;
            for (i = 0; i < list.length; i++) {
                if (list[i].date === obj) {
                    if (!flag) {
                        flag = true;
                    } else {
                        return list[i];
                    }
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

        function isDateOverLimit(date) {
            temp = date.split('-');
            date = temp[0] + (temp[1].length == 1 ? '0' + temp[1] : temp[1]) + (temp[2].length == 1 ? '0' + temp[2] : temp[2]);
            var date_num = parseInt(date);
            if (date_num > maxDayShamsi2) {
                return 1;
            } else if (date_num == maxDayShamsi2) {
                return 0;
            }
            return -1;
        }

        var periodic_costs_string = '<?php echo json_encode($periodic_costs); ?>';
        var periodic_costs = JSON.parse(periodic_costs_string);

        function getCostForAday(dayOfWeek, shift) {
            return periodic_costs[dayOfWeek].shifts[shift].cost;
        }

        var classname = document.getElementsByClassName("pwt-btn");
        for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', ArrangeCalender, false);
        }

        ArrangeCalender();

        function isMonthChanged() {
            var x = document.getElementsByClassName("pwt-btn-switch")[0].innerText;
            if (x == lastMonth)
                return false;
            return true;
        }

        function ArrangeCalender() {
            lastMonth = document.getElementsByClassName("pwt-btn-switch")[0].innerText;
            var tds = $('#bigCalender .table-days td');

            for (var i = 0; i < tds.length; i++) {
                var temp0 = tds[i].getAttribute("data-date");
                temp1 = changeFromatDate(temp0);
                var temp_mod = i % 7;
                var t = isDatelast(temp1);
                var t2 = isDateOverLimit(temp1);
                if (t > 0) {
                    continue;
                } else if (t == 0) {
                    tds[i].innerHTML = tds[i].innerHTML + "<span style='color:#1f85d0' >امروز</span>";
                    continue;
                }
                if (t2 > 0) {
                    return;
                }
                if (on_time_holidays_string.includes("\"" + temp1 + "\"")) {
                    tds[i].innerHTML = tds[i].innerHTML + "<span style='color:red' >تعطیل</span>";
                } else if (reserved_dates_string.includes("\"" + temp1 + "\"")) {
                    switch (containsObject(temp1, reserved_dates).shift_id) {
                        case "0":
                            if (containsObject_second(temp1, reserved_dates).shift_id == 1) {
                                tds[i].style.backgroundImage = "url('{{url('/images/fill-td.png')}}')";
                                tds[i].innerHTML = tds[i].innerHTML + "<span style='color:gray' >رزرو شده</span>";
                            } else {
                                tds[i].style.backgroundImage = "url('{{url('/images/half-fill-td.png')}}')";
                                tds[i].style.backgroundSize = "100% 100%";
                                tds[i].innerHTML = tds[i].innerHTML + 'عصر:' + getCostForAday(temp_mod, 1) / 1000 + ' هزارتومان';
                            }
                            break;
                        case "1":
                            if (containsObject_second(temp1, reserved_dates).shift_id == 0) {
                                tds[i].style.backgroundImage = "url('{{url('/images/fill-td.png')}}')";
                                tds[i].innerHTML = tds[i].innerHTML + "<span style='color:gray' >رزرو شده</span>";
                            } else {
                                tds[i].style.backgroundImage = "url('{{url('/images/half-fill-td2.png')}}')";
                                tds[i].style.backgroundSize = "100% 100%";
                                tds[i].innerHTML = tds[i].innerHTML + 'صبح:' + getCostForAday(temp_mod, 0) / 1000 + ' هزارتومان';
                            }
                            break;
                        default:
                            tds[i].style.backgroundImage = "url('{{url('/images/fill-td.png')}}')";
                            tds[i].innerHTML = tds[i].innerHTML + "<span style='color:gray' >رزرو شده</span>";
                    }

                } else {

                    var ff = $.inArray(temp_mod, ph);
                    if (ff == -1) {
                        tds[i].innerHTML = tds[i].innerHTML + getCostForAday(temp_mod, 2) / 1000 + ' هزارتومان';
                    } else {
                        tds[i].innerHTML = tds[i].innerHTML + "<span style='color:red' >تعطیل</span>";
                    }
                }
            }
        }

        //selected day
        function checkSelectedDate(isStart) {
            if (isStart)
                var SelectedDay = document.getElementById('start_date').value;
            else
                var SelectedDay = document.getElementById('end_date').value;
            var SelectedDay2 = parseInt(parseArabic((SelectedDay.replace('/', '')).replace('/', '').toString()));
            if (SelectedDay2 == todayDateShamsi2) {
                swal('از انتخاب امروز دیگر گذشت');
            } else if (SelectedDay2 < todayDateShamsi2) {
                swal('روزهای گذشته، دیگر گذشته اند');
            } else if (SelectedDay2 > maxDayShamsi2) {
                swal('تاریخ انتخاب شده بیش تر از حد قابل قبول است');
            } else {
                var temp = SelectedDay2.toString();
                var SelectedDay3 = temp.substr(0, 4) + '-' + (temp.substr(4, 2).charAt(0) == "0" ? temp.substr(4, 2).charAt(1) : temp.substr(4, 2)) + '-' + (temp.substr(6, 2).charAt(0) == "0" ? temp.substr(6, 2).charAt(1) : temp.substr(6, 2));
                if (containsObject(SelectedDay3, reserved_dates)) {
                    if (containsObject(SelectedDay3, reserved_dates).shift_id == "0") {
                        if (containsObject_second(SelectedDay3, reserved_dates).shift_id == "1") {
                            swal('تاریخ انتخابی پر است!');
                        } else {
                            swal({
                                type: 'warning',
                                title: 'توجه کنید که این تاریخ برای صبح رزرو شده است.بنابراین فقط مجاز به انتخاب بازه ی شب می باشید'
                            })
                        }
                    } else if (containsObject(SelectedDay3, reserved_dates).shift_id == "1") {
                        if (containsObject_second(SelectedDay3, reserved_dates).shift_id == "0") {
                            swal('تاریخ انتخابی پر است!');
                        } else {
                            swal({
                                type: 'warning',
                                title: 'توجه کنید که این تاریخ برای شب رزرو شده است.بنابراین فقط مجاز به انتخاب بازه ی صبحش می باشید'
                            })
                        }
                    } else {
                        swal('تاریخ انتخابی پر است!');
                    }
                } else if (on_time_holidays_string.includes("\"" + SelectedDay3 + "\"")) {
                    swal('در این تاریخ باغ تعطیل است');
                }
            }
        }

        function parseArabic(str) {
            return Number(str.replace(/[٠١٢٣٤٥٦٧٨٩]/g, function (d) {
                return d.charCodeAt(0) - 1632; // Convert Arabic numbers
            }).replace(/[۰۱۲۳۴۵۶۷۸۹]/g, function (d) {
                return d.charCodeAt(0) - 1776; // Convert Persian numbers
            }));
        }


                {{--meysam--}}
        var latitude = "<?php echo $garden->latitude; ?>";
        var longitude = "<?php echo $garden->longitude; ?>";
        var name = "<?php echo $garden->name; ?>";
        var chooseText = "<?php echo trans('messages.txtChoose') ?>";

        var activeStatus = '<?php echo \App\Garden::StatusActive ?>';

        $(document).ready(function () {
            var map;
            if (!latitude) {
                map = L.map('map').setView([29.617248, 52.543423], 16);
            }
            else {
                map = L.map('map').setView([latitude, longitude], 16);
            }

            L.tileLayer('https://developers.parsijoo.ir/web-service/v1/map/?type=tile&x={x}&y={y}&z={z}&apikey=4a910bd09861449b834625701bad4f2d',
                {
                    maxZoom: 19,
                }).addTo(map);

            if (latitude) {
                var popup = L.popup()
                    .setLatLng([latitude, longitude])
                    .setContent(name)
                    .openOn(map);

                var marker = L.marker([latitude, longitude], {
                    icon: L.icon({
                        // iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
                        iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                        // className: 'blinking'
                    })
                }).addTo(map);
            }
        })

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

        function doReloadShift(dayOfWeekElementId, shiftElementId) {
            //meysam - reload shifts...
            var dayElement = document.getElementById(dayOfWeekElementId);
            var dayOfWeek = dayElement.options[dayElement.selectedIndex].value;
            $("#" + shiftElementId)
                .find('option')
                .remove()
                .end()
            ;
            var shiftBody = "<option value=''>" + chooseText + "</option>";
            $("#" + shiftElementId).append(shiftBody);


            // alert('dayOfWeek:'+dayOfWeek);

            var temp = '<?php echo json_encode($periodic_costs) ?>';
            var daysOfWeek = JSON.parse(temp);
            var length = daysOfWeek.length;

            // alert('daysOfWeek:'+daysOfWeek);

            for (var i = 0; i < length; i++) {
                // alert('daysOfWeek[i].day_of_week:'+daysOfWeek[i].day_of_week);
                // alert('dayOfWeek:'+dayOfWeek);
                if (daysOfWeek[i].day_of_week == dayOfWeek) {
                    // alert('haaaa!!!');
                    var shifts = daysOfWeek[i].shifts;
                    var shiftLength = shifts.length;
                    // alert('shiftLength: '+shiftLength);
                    for (var j = 0; j < shiftLength; j++) {

                        // alert('shifts[j].status: '+shifts[j].status);
                        // alert('activeStatus: '+activeStatus);


                        if (shifts[j].status == activeStatus) {
                            // alert('haaaa!!!');
                            var shiftBody = "<option value=" + shifts[j].shift_id + ">" + shifts[j].name + "=>" + shifts[j].open_time + "تا" + shifts[j].close_time + "</option>";
                            $("#" + shiftElementId).append(shiftBody);
                        }
                    }

                    break;

                }

            }

        }

        //
        $(function () {
            $('#start_date_show').persianDatepicker({
                minDate: new persianDate().unix(),
                initialValue: false,
                altField: '#start_date',
                altFormat: 'L',
                autoClose: true,
                format: 'dddd, DD MMMM YYYY',
                toolbox: {
                    calendarSwitch: {
                        enabled: false
                    }
                },
                checkDate: function (unix) {
                    var flag = true;
                    for (var i = 0; i < ph.length; i++) {
                        flag = flag && new persianDate(unix).day() != (ph[i] + 1);
                    }
                    return flag;
                },
                onSelect: function () {
                    startDateSelected();
                },
                dayPicker: {
                    onSelect: function () {
                        checkSelectedDate(true);
                    }
                }
            });
        });

        function startDateSelected() {
            var temp = $('#start_date_show').val();
            temp = temp.substr(0, temp.indexOf(','));
            document.getElementById("start_week_day_id").selectedIndex = getValueForSelector(temp);
            doReloadShift('start_week_day_id', 'shift_id_1');
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

        $(function () {
            $('#end_date_show').persianDatepicker({
                initialValue: false,
                altField: '#end_date',
                altFormat: 'L',
                format: 'dddd, DD MMMM YYYY',
                autoClose: true,
                minDate: new persianDate().unix(),
                toolbox: {
                    calendarSwitch: {
                        enabled: false
                    }
                },
                checkDate: function (unix) {
                    var flag = true;
                    for (var i = 0; i < ph.length; i++) {
                        flag = flag && new persianDate(unix).day() != (ph[i] + 1);
                    }
                    return flag;
                },
                onSelect: function () {
                    endDateSelected();
                },
                dayPicker: {
                    onSelect: function () {
                        checkSelectedDate(false);
                    }
                }
            });
        });

        function endDateSelected() {
            var temp = $('#end_date_show').val();
            temp = temp.substr(0, temp.indexOf(','));
            document.getElementById("end_week_day_id").selectedIndex = getValueForSelector(temp);
            doReloadShift('end_week_day_id', 'shift_id_2');
        }

        $(document).ready(function () {
        });


        // function myCaptchaCallback() {
        //
        //     // var vl =  $('.verify-me-text').val() ;
        //     // alert(vl);
        //     //   if( typeof(vl) == 'undefined' ){
        //     //       return "yes"; // return 0 as replace, and end function execution
        //     //       alert("yes");
        //     //       a ="hi";
        //     //       alert(a);
        //     //   }
        //     //   return "no";
        //     //   alert("no");
        //
        //
        //     $('div.verified-container div.verified-text').text('New text');
        //     $('.verify-me-text').text('New text');
        //     $('.verify-me-text').html('New text');
        //     $('.verified-text').html('New text');
        //
        //     // alert(jQuery('.verified-container').find('.verified-text').val());
        // }

        function showDetail(x) {
            for (i = 1; i < 39; i++) {
                var popup = document.getElementById("myPopup" + i);
                popup.classList.remove("show");
            }
            var popup = document.getElementById("myPopup" + x);
            popup.classList.toggle("show");
        }

        function HideDetail(x) {
            for (i = 1; i < 39; i++) {
                var popup = document.getElementById("myPopup" + i);
                popup.classList.remove("show");
            }
            var popup = document.getElementById("myPopup" + x);
            popup.classList.remove("show");
        }


    </script>
@stop