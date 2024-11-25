@extends('layouts.master')
@section('title',  trans('messages.tltFeedback'))

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



    <div class="row" style="margin-top: 40px">
        <div class="col-md-1"></div>
        <div class="col-md-6 text-direction text-center-mobile wow animated fadeInUp contact-details"
             style="padding:0px 30px;margin-bottom: 30px">
            <h2 class="heading no-margin wow animated fadeInUp">با ما همراه باشید</h2>
            <h3 class="subheading muted no-margin wow animated fadeInUp">ما آماده پاسخگویی در تمامی زمینه ها
                ی مربوط به فعالیت های سایت هستیم. لطفا جهت ارتباط با ما درخواست خود را در فرم مقابل ثبت نمایید. </h3>
            <div class="details">
                {{--<h4 class="heading no-margin">منتظر شما هستیم</h4>--}}
                <p class="muted wow animated fadeInUp no-margin">
                    هرگونه پیشنهاد، انتقاد، درخواست و یا سوالات خود را برای ما از طریق فرم ارسال کنید و یا با شماره تلفن و پست الکترونیکی زیر با ما ارتباط باشید.<br>

                </p>
            </div>
            <div class="details">
                {{--<h6 class="subheading accent no-margin wow animated fadeInUp"> 07132221402 <br>--}}
                {{--در صورت عدم پاسخگویی تلفن درخواست خود را به support@attentra.ir ارسال کنید</h6>--}}
                <h4 class="subheading accent no-margin wow animated fadeInUp"> 09029027302 </h4>
                <h4 class="subheading accent no-margin wow animated fadeInUp">
                    در صورت عدم پاسخگویی تلفن، درخواست یا سوال خود را به support@shirazpicnic.ir ارسال کنید</h4>
            </div>
        </div>

        <div class="col-md-4 text-direction text-center-mobile wow  fadeInUp contact-details animated"
             style="padding:0px 30px;">
            <div>
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
            {{--        {{ Form::open(array('url' => 'feedbacks/store')) }}--}}
            <form name="feedback_form" class="form-horizontal" role="form" method="POST"
                  action="{{ url('/feedbacks/store') }}">
                {{ csrf_field() }}
                <fieldset>
                    <legend>فرم تماس با ما</legend>
                    <div class="form-group">
                        <label for="email" class="col-md-4 control-label">    {{trans('messages.lblEmail')}}</label>

                        <div class="col-md-12 col-md-offset-4">
                            <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name_and_family"
                               class="col-md-4 control-label">    {{trans('messages.lblNameAndFamily')}}</label>

                        <div class="col-md-12 col-md-offset-4">
                            {{--                {{ Form::text('mobile', null, array('class'=>'form-control','id'=>'mobile', 'placeholder'=>'تلفن...')) }}--}}
                            <input id="name_and_family" type="text" class="form-control" name="name_and_family"
                                   value="{{ old('name_and_family') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tel" class="col-md-4 control-label">    {{trans('messages.lblTel')}}</label>

                        <div class="col-md-12 col-md-offset-4">
                            {{--                {{ Form::text('tel', null, array('class'=>'form-control','id'=>'tel', 'placeholder'=>'تلفن...')) }}--}}
                            <input id="tel" type="text" class="form-control" name="tel" value="{{ old('tel') }}">

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title" class="col-md-4 control-label">    {{trans('messages.lblTitle')}}</label>

                        <div class="col-md-12 col-md-offset-4">
                            {{--                {{ Form::textarea('title', null, array('class'=>'form-control','data-validation'=>'required','id'=>'title', 'placeholder'=>'عنوان. . .')) }}--}}
                            <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}"
                                   required>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description"
                               class="col-md-4 control-label">    {{trans('messages.lblDescription')}}</label>

                        <div class="col-md-12 col-md-offset-4">
                            {{--                {{ Form::textarea('description', null, array('class'=>'form-control','data-validation'=>'required','id'=>'description', 'placeholder'=>'توضیحات. . .')) }}--}}
                            <textarea id="description" name="description" class="form-control"
                                      value="{{ old('description') }}"></textarea>

                        </div>
                    </div>

                    {{--<div class="g-recaptcha" data-sitekey="6LcEmlkUAAAAACSS899mhlETuIM_WR4KKBeiRz3T"></div>--}}
                    <div class="col-md-12 g-recaptcha" id="recaptcha3"></div>


                    <div class="col-md-12 form-group">
                        <div class="col-md-8 col-md-offset-4" style="">
                            <button type="submit" class="btn btn-primary"
                                    style="width: 100%;margin-top: 15px;display: block;margin-right: auto;margin-left: auto">
                                {{trans('messages.btnSubmit')}}
                            </button>
                        </div>
                    </div>
                </fieldset>
                {{--        {{ Form::close() }}--}}
            </form>

        </div>
        <div class="col-md-1"></div>
    </div>
@endsection