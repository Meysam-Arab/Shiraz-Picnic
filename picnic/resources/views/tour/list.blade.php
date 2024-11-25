@extends('layouts.master')
@section('title', trans('messages.tltTours'))

@section('content')
    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */
    use Illuminate\Support\Facades\Log;
    ?>

    <div id="fh5co-events" data-section="events">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 style="text-align: center">تورها</h3>
                </div>
            </div>
            <div class="row">
                @if(isset($tours))
                    @foreach($tours as $tour)
                        <?php
                        $is_ended = false;
                        $is_deadline = false;
                        $temp_start_date = \Carbon\Carbon::parse($tour->miladi_start_date_time);
                        $temp_start_date = \Carbon\Carbon::parse($temp_start_date->toDateString());
                        if($temp_start_date->lt(\Carbon\Carbon::now()))
                            $is_ended = true;

                        $temp_deadline_date_time = \Carbon\Carbon::parse($tour->miladi_deadline_date_time, "Asia/Tehran");
                        if ($temp_deadline_date_time->lt(\Carbon\Carbon::now("Asia/Tehran")))
                            $is_deadline = true;

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
                                <img src="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/1/3.33')}}" class="img-responsive2" style="min-height: 200px;max-height: 200px;min-width: 100%;max-width: 100%;"
                                     alt="شیراز پیک نیک -  {{$tour->title}}">
                                <div class="container" style="position: relative;">
                                    <img src="{{URL::to('users/getFile/'.$tour->owner->user_id.'/'.$tour->owner->user_guid.'/1.11')}}" class="header-profile circle" alt="شیراز پیک نیک -  {{$tour->title}}">
                                </div>
                                <span class="fh5co-event-meta"
                                      style="margin-top: 10px; word-break: break-all;height: 50px;  overflow-y: auto;">

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

                                    </span>

                                @if($is_deadline)


                                    @if($is_ended)
                                        <p class="text_01a" style="color: #00c500"> برگزار شد</p>
                                    @else
                                        <p class="text_01a" style="color: #0095c5"> مهلت ثبت نام به اتمام رسیده</p>
                                    @endif
                                    @if($is_in_one_day)
                                        <p >  {{$tour->start_date_time}} <br><br> </p>
                                    @else
                                        <p >  {{$tour->start_date_time}} از <br>{{$tour->end_date_time}} تا </p>
                                    @endif

                                    <p><a href="{{url("/tours/detail/{$tour->tour_id}")}}" class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>

                                @else
                                    @if($is_ended_capacity)
                                        <p class="text_01a" style="color: #c50100"> ظرفیت تکمیل</p>
                                    @else
                                        @if($tour->cost == 0)
                                            <p style="direction: rtl"> رایگان</p>

                                        @else
                                            @if($tour->stroked_cost != null && $tour->stroked_cost > 0)
                                                <p style="direction: rtl"><del>{{number_format($tour->stroked_cost)}} </del>&nbsp; {{number_format($tour->cost)}} {{trans('messages.lblToman')}}</p>
                                            @else
                                                <p style="direction: rtl"> {{number_format($tour->cost)}} {{trans('messages.lblToman')}}</p>
                                            @endif

                                        @endif

                                    @endif

                                        @if($is_in_one_day)
                                            <p >  {{$tour->start_date_time}} <br> <br></p>
                                        @else
                                            <p >  {{$tour->start_date_time}} از <br>{{$tour->end_date_time}} تا </p>
                                        @endif

                                    <p><a href="{{url("/tours/detail/{$tour->tour_id}")}}" class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>

                                @endif


                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection