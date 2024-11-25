@extends('layouts.new1master',['main_blade' => 'yes'])

@section('title', 'تورهای تفریحی و ورزشی - اجاره باغ های عمومی و خصوصی')
@section('description')
    {{'شیراز پیک نیک محلی برای اجاره باغ های عمومی و خصوصی و رزرو تورهای تفریحی و گردشگری'}}
@endsection

{{--@section('title', 'تورهای تفریحی و ورزشی - اجاره باغ های عمومی و خصوصی')--}}
{{--@section('description', 'شیراز پیک نیک محلی برای اجاره باغ های عمومی و خصوصی و رزرو تورهای تفریحی و گردشگری')--}}
@section('content')
    @include('home.sliderTop')
    @include('home.middleBar')



    <div class="whole-wrap">
        <div class="container">
            <div class="section-top-border" id="fh5co-events">
                <h2 class="mb-30 title_color" style="text-align: center">تورهای پیش رو</h2>
                <div class="row">
                    @if(isset($tours))
                        @foreach($tours as $tour)
                            <?php

                            $is_ended = false;
                            $is_deadline = false;

                            $temp_start_date = \Carbon\Carbon::parse($tour->miladi_start_date_time, "Asia/Tehran");
                            if ($temp_start_date->lt(\Carbon\Carbon::now("Asia/Tehran")))
                                $is_ended = true;

                            $temp_deadline_date_time = \Carbon\Carbon::parse($tour->miladi_deadline_date_time, "Asia/Tehran");
                            if ($temp_deadline_date_time->lt(\Carbon\Carbon::now("Asia/Tehran")))
                                $is_deadline = true;

                            $temp_start_date = \Carbon\Carbon::parse($temp_start_date->toDateString());
                            $temp_end_date = \Carbon\Carbon::parse($tour->miladi_end_date_time);
                            $temp_end_date = \Carbon\Carbon::parse($temp_end_date->toDateString());
                            $is_in_one_day = false;
                            if ($temp_start_date->eq($temp_end_date))
                                $is_in_one_day = true;

                            $is_ended_capacity = false;
                            if($tour->remaining_capacity <= 0)
                                $is_ended_capacity = true;
                            ?>
                            <div class="col-md-4" style="float: right; ">
                                <div class="fh5co-event to-animate-2">
                                    <h3>{{$tour->title}}</h3>
                                    <img src="{{URL::to('tours/getFile/'.$tour->tour_id.'/'.$tour->tour_guid.'/1/3.33')}}"
                                         class="img-responsive2" style="min-height: 200px;max-height: 200px;min-width: 100%;max-width: 100%;"
                                         alt="شیراز پیک نیک -  {{$tour->title}}">
                                    <div class="container" style="position: relative;">
                                        <img src="{{URL::to('users/getFile/'.$tour->owner->user_id.'/'.$tour->owner->user_guid.'/1.11')}}"
                                             class="header-profile circle" alt="شیراز پیک نیک -  {{$tour->title}}">
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
                                            <p>  {{$tour->start_date_time}} <br><br></p>
                                        @else
                                            <p>  {{$tour->start_date_time}} از <br>{{$tour->end_date_time}} تا </p>
                                        @endif

                                        <p><a href="{{url("/tours/{$tour->tour_id}")}}"
                                              class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>

                                    @else

                                        @if($is_ended_capacity)
                                            <p class="text_01a" style="color: #c50100"> ظرفیت تکمیل</p>
                                        @else
                                            @if($tour->cost == 0)
                                                <p class="text_01a"> رایگان</p>
                                            @else
                                                @if($tour->stroked_cost != null && $tour->stroked_cost > 0)
                                                    <p class="text_01a">
                                                        <del>{{number_format($tour->stroked_cost)}} </del> &nbsp;  {{number_format($tour->cost)}} {{trans('messages.lblToman')}}</p>
                                                @else
                                                    <p class="text_01a"> {{number_format($tour->cost)}} {{trans('messages.lblToman')}}</p>
                                                @endif
                                            @endif

                                        @endif


                                        @if($is_in_one_day)
                                            <p>  {{$tour->start_date_time}} <br><br></p>
                                        @else
                                            <p>  {{$tour->start_date_time}} از <br>{{$tour->end_date_time}} تا </p>
                                        @endif
                                        <p><a href="{{url("/tours/{$tour->tour_id}")}}"
                                              class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>

                                    @endif


                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"></div>
            <a href="{{url('/tours/listAll')}}" class="btn btn-primary btn-down-01"
               style="margin: 0px auto 30px auto;display: block;max-width: 60%;">نمایش تمامی تور ها</a>
        </div>
    </div>
    <div class="whole-wrap">
        <div class="container">
            <div class="section-top-border" id="fh5co-events">
                <h2 class="mb-30 title_color" style="text-align: center">بلاگ ها</h2>
                <div class="row">
                    @if(isset($blogs))
                        @foreach($blogs as $blog)
                            <?php
                            $temp_date = \Carbon\Carbon::parse($blog->miladi_blog_date_time);
                            $temp_date = \Carbon\Carbon::parse($temp_date->toDateString());

                            ?>
                            <div class="col-md-4" style="float: right">
                                <div class="fh5co-event to-animate-2">
                                    <h3>{{$blog->title}}</h3>

                                    @if($blog->hasCoverPicture)
                                        <img src="{{URL::to('blogs/getFile/'.$blog->blog_id.'/'.$blog->blog_guid.'/null/5.33')}}"
                                             class="img-responsive2" style="min-height: 200px;max-height: 200px;"
                                             alt="شیراز پیک نیک -  {{$blog->title}}">
                                    @else
                                        <img src="{{URL::to('images/empty.jpg')}}"
                                             class="img-responsive2" style="min-height: 200px;max-height: 200px;"
                                             alt="شیراز پیک نیک -  {{$blog->title}}">
                                    @endif


                                    <p>  {{$blog->blog_date_time}} <br><br></p>

                                    <p><a href="{{url("/blogs/{$blog->blog_id}")}}"
                                          class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>


                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"></div>
            <a href="{{url('/blogs/catalog')}}" class="btn btn-primary btn-down-01"
               style="margin: 0px auto 30px auto;display: block;max-width: 60%;">نمایش تمامی بلاگ ها</a>
        </div>
    </div>
    <div id="fh5co-featured" data-section="features" style="margin-bottom: 20px" class="animated">
        <div class="container">
            <div class="row text-center fh5co-heading row-padded">
                <div class="col-md-12 col-md-offset-2">
                    <h2 class="heading to-animate fadeInUp animated">باغ های اجاره ای</h2>
                    <p class="sub-heading to-animate fadeInUp animated">با کلیک بر روی هر باغ می توانید جزئیات آن را
                        مشاهده کنید</p>
                </div>
            </div>
            <div class="row">
                <div class="fh5co-grid">
                    <div class="fh5co-v-half to-animate-2 bounceIn animated">
                        <div class="fh5co-v-col-2 fh5co-bg-img"
                             style="background-image: '{{url('/images/mainPage/slide_4.jpg')}}'; direction: rtl"></div>
                        <div class="fh5co-v-col-2 fh5co-text fh5co-special-1 arrow-left" >
                            <h2>باغ چهار فصل</h2>
                            <span class="pricing">...</span>
                            <p>45 کیلومتری شیراز</p>
                            <div class="form-group ">
                                <a class="btn btn-primary" disabled="true">به زودی</a>
                            </div>
                        </div>
                    </div>
                    <div class="fh5co-v-half">
                        <div class="fh5co-h-row-2 to-animate-2 bounceIn animated">
                            <div class="fh5co-v-col-2 fh5co-bg-img"
                                 style="background-image: '{{url('/images/mainPage/slide_3.jpg')}}' "></div>
                            <div class="fh5co-v-col-2 fh5co-text arrow-left" style="direction: rtl">
                                <h2>محل تبلیغ باغ شما</h2>
                                <span class="pricing">...</span>
                                <p>...</p>
                            </div>
                        </div>
                        <div class="fh5co-h-row-2 fh5co-reversed to-animate-2 bounceIn animated">
                            <div class="fh5co-v-col-2 fh5co-bg-img"
                                 style="background-image: '{{url('/images/mainPage/slide_1.jpg')}}'"></div>
                            <div class="fh5co-v-col-2 fh5co-text arrow-right" style="direction: rtl">
                                <h2>محل تبلیغ باغ شما</h2>
                                <span class="pricing">...</span>
                                <p>...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: none">
                <div class="col-md-12"></div>
                <a class="btn btn-primary btn-down-01" style="margin: 0px auto;display: block;max-width: 60%;"
                   disabled>نمایش تمامی باغ ها</a>
            </div>
        </div>
    </div>
    <div id="fh5co-contact" data-section="reservation" style="direction: rtl">
        <div class="container">
            <div class="row text-center fh5co-heading row-padded">
                <div class="col-md-12">
                    <h2 class="heading to-animate">تماس با ما</h2>
                    <p class="sub-heading to-animate">در صورت تمایل جهت قرار دادن تور یا باغ خود در سایت با ما تماس
                        بگیرید</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 to-animate-2">
                    <div class="col-md-12">
                        <h3 style="text-align: center">فرم تماس</h3>
                        <div class="form-group ">
                            <label for="name_and_family" class="sr-only">نام و نام خانوادگی شما</label>
                            <input id="name_and_family" name="name_and_family" class="form-control"
                                   placeholder="نام و نام خانوادگی شما" type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label for="tel" class="sr-only">{{trans('messages.lblTel')}}</label>
                                <input id="tel" name="tel" class="form-control"
                                       placeholder="{{trans('messages.lblTel')}}"
                                       type="tel">
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
                                    <option>درخواست اجاره باغ یا قراردادن تور در سایت</option>
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
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="form-group refereshrecapcha">
                                    {!! captcha_img('flat') !!}
                                </div>
                                <button class="btn" style="background-color: #6aaedf"
                                        onclick="refreshCaptcha()">{{trans('messages.btnRefresh')}}</button>
                                <p style="text-align: center;">{{trans('messages.lblCaptcha')}}:<input type="text"
                                                                                                       name="captcha"
                                                                                                       id="captcha"></p>

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
                                       style="min-width: 30%"
                                       type="submit">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 to-animate-2" style="padding: 20px">
                    <div class="box_001">
                        <h3 style="text-align: center">اطلاعات تماس</h3>
                        <ul class="fh5co-contact-info" style="direction: rtl;text-align: right;">
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
    <div id="dialog-processing" title="{{trans('messages.tltDialogProcessing')}}" s>
        <p>{{trans('messages.msgDialogProcessing')}}</p>
    </div>
    @include('home.footer')
    </div>
    </div>
@endsection

@section('page-js-files')
    <script src="{{URL::to('assets-switalert/sweetalert2.all.min.js')}}"></script>
@stop


@section('page-js-script')
    <script type="text/javascript">

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

            $("#dialog-processing").dialog("open");

            var urlTo = '{{url("/feedbacks/store")}}';

            var form_data = new FormData();
            var description = document.getElementById("description").value;
            var email = document.getElementById("email").value;
            var tel = document.getElementById("tel").value;
            var name_and_family = document.getElementById("name_and_family").value;
            var occasionElement = document.getElementById("occasion");
            var title = occasionElement.options[occasionElement.selectedIndex].value;
            var captcha = document.getElementById("captcha").value;


            form_data.append("title", title);
            form_data.append("description", description);
            form_data.append("email", email);
            form_data.append("tel", tel);
            form_data.append("name_and_family", name_and_family);
            form_data.append("captcha", captcha);

            var url = urlTo;

            alert(url);

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
@stop

