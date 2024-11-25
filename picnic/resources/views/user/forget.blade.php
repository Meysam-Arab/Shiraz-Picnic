@extends('layouts.master')
@section('title',  trans('messages.tltForgetPassword'))


@section('content')
    <div class="container">
        <div class="row" style="margin-top: 40px;margin-bottom: 100px; direction: rtl">
            <fieldset class="direction">
                <legend>
                    <h5 class="mb-0">
                        {{trans('messages.tltForgetPassword')}}
                    </h5>
                </legend>
                <div class="col-md-12">
                    @if(count($errors) > 0)
                        <div class="alert alert-danger">
                            @foreach($errors as $error)
                                <ul style="color: darkred">
                                    <li style="text-align: center" colspan="2">{{$error}}</li>
                                </ul>
                            @endforeach
                        </div>
                        {{ session()->forget('errors')}}
                    @endif

                    @if(session('messages'))
                        <div class="alert alert-info">
                            @foreach (session('messages') as $message)
                                <ul style="color: green">
                                    <li style="text-align: center" colspan="2">{{ $message}}</li>
                                </ul>
                            @endforeach
                        </div>
                        {{ session()->forget('messages')}}
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                       <fieldset>
                           <legend>توضیحات</legend>
                            برای تغییر رمز عبور خود، بعد از وارد کردن ایمیل/شماره موبایل و رمزهای جدید، یک ایمیل/پیامک برای شما ارسال خواهد شد که در آن لینک تاییده ای وجود دارد که با کلیک روی آن رمز شما به رمز مورد نظر تغییر خواهد کرد. در صورت دریافت نکردن ایمیل/پیامک پوشه هرزنامه (spam) برنامه مربوطه را نیز بررسی نمایید.
                       </fieldset>
                    </div>
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/users/forget') }}">
                            {{ csrf_field() }}

                            <div class="form-group" style="margin-top: 10px">
                                <label for="email" class="col-md-4 control-label" style="float: right">{{trans('messages.lblEmailOrMobile')}}</label>

                                <div class="col-md-8">
                                    <input id="email" type="text" class="form-control" name="email"
                                           value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="new_password" style="float: right"
                                       class="col-md-4 control-label">{{trans('messages.lblPassword')}} جدید</label>

                                <div class="col-md-8">
                                    <input id="new_password" type="password" class="form-control" name="new_password"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="new_password_confirmation" style="float: right"
                                       class="col-md-4 control-label">{{trans('messages.lblPasswordConfirmation')}}</label>

                                <div class="col-md-8">
                                    <input id="new_password_confirmation" type="password" class="form-control"
                                           name="new_password_confirmation" required>
                                </div>
                            </div>

                            <div class="refereshrecapcha">
                                {!! captcha_img() !!}
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

                            <div class="form-group" style="margin-top: 5px">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" style="display: block;margin-left: auto;margin-right: auto">
                                        {{trans('messages.btnSubmit')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>



@endsection
@section('page-js-script')
    <script type="text/javascript">

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
                    // alert('Try Again.'+data);
                    swal({
                        position: 'center',
                        type: 'error',
                    })
                }
            });

            return false;
        }

    </script>
@stop