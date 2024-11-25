@extends('layouts.master')
@section('title', trans('messages.lblRegister'))

@section('content')
    <!-- Latest compiled and minified CSS -->
    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */

    use Illuminate\Support\Facades\Log;

    ?>

    <!-- Latest compiled and minified JavaScript -->
    <div class="registerFrom" style="direction: rtl">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4"></div>
                    <div class="col-md-4" style="text-align: center">
                        <h2>
                            {{trans('messages.lblRegister')}}
                        </h2>
                    </div>
                    <div class="col-md-4"></div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
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
                    <div class="col-md-1"></div>
                </div>
                <form name="register_form" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data"
                      action="{{ url('/users/register') }}">
                    <div class="col-md-12" style="margin-top: 20px; direction: rtl">
                        {{ csrf_field() }}



                        @if(Auth::user()->type == \App\User::TypeAdmin || Auth::user()->type == \App\User::TypeOperator)
                            <div class="row" style="direction: rtl">
                                <div class="col-md-12">
                                    <div class="col-md-6" style="margin-top:10px;margin-bottom: 30px;">
                                        <label class="float"> {{trans('messages.lblStatus')}}</label><span style=" color: red;">★</span>
                                        <select id="status" name="status">
                                            @foreach(\App\User::Statuses as $status)
                                                <option value='{{$status}}'>{{\App\User::getStatusStringByCode($status)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6" style="margin-top:10px;margin-bottom: 30px;">
                                        <label class="float"> {{trans('messages.lblType')}}:</label><span style=" color: red;">★</span>
                                        <select id="type" name="type">
                                            @foreach(\App\User::Types as $type)
                                                <option value='{{$type}}'>{{\App\User::getTypeStringByCode($type)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <div class="col-md-6">
                            <fieldset>
                                <legend>{{trans('messages.lblPersonalInformation')}}</legend>
                                <div class="form-group" style="direction: rtl">
                                    <label for="name"
                                           class="col-md-4 control-label">{{trans('messages.lblName')}}<span style=" color: red;">★</span></label>

                                    <div class="col-md-8 col-md-offset-4">
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{ old('name') }}" autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="family"
                                           class="col-md-4 control-label">{{trans('messages.lblFamily')}}<span style=" color: red;">★</span></label>

                                    <div class="col-md-8 col-md-offset-4">
                                        <input id="family" type="text" class="form-control" name="family"
                                               value="{{ old('family') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email"
                                           class="col-md-4 control-label">    {{trans('messages.lblEmail')}}<span style=" color: red;">★</span></label>
                                    <div class="col-md-8 col-md-offset-4">
                                        <input id="email" type="email" class="form-control" name="email"
                                               value="{{ old('email') }}" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password"
                                           class="col-md-4 control-label">    {{trans('messages.lblPassword')}}<span style=" color: red;">★</span></label>
                                    <div class="col-md-8 col-md-offset-4">
                                        <input id="password" type="password" class="form-control" name="password"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation"
                                           class="col-md-4 control-label">    {{trans('messages.lblRepeat_password')}}<span style=" color: red;">★</span></label>

                                    <div class="col-md-8 col-md-offset-4">
                                        <input id="password_confirmation" type="password" class="form-control"
                                               name="password_confirmation" required>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>{{trans('messages.lblComplementaryInformation')}}</legend>
                                <div class="form-group">
                                    <label for="mobile"
                                           class="col-md-4 control-label">{{trans('messages.lblMobile')}}<span style=" color: red;">★</span></label>

                                    <div class="col-md-8 col-md-offset-4">
                                        <input id="mobile" type="tel" class="form-control" name="mobile"
                                               value="{{ old('mobile') }}" required>
                                    </div>
                                </div>


                                <div class="form-group" style="margin-top: 45px">
                                    <label for="inviterCode"
                                           class="col-md-4 control-label">{{trans('messages.lblSocial')}}</label>

                                    <div id="socials">
                                        <div id="div_social_0">
                                            {{--نام:<input id="social_name_0" name="social_name_0" type="text">--}}
                                            نام:<select id="social_name_0" name="social_name_0">
                                                <option value=''>انتخاب کنید</option>
                                                @foreach(\App\User::Socials as $social)
                                                    <option value='{{$social}}'>{{\App\User::getSocialStringByCode($social)}}</option>
                                                @endforeach

                                            </select>
                                            آدرس: <input id="social_address_0" name="social_address_0" type="text">
                                        </div>

                                    </div>
                                    <div id="remove_social"></div>
                                    <button  type="button" id="add_social" onclick="addSocial()">افزودن</button>
                                    <br> <br>
                                </div>


                                <div class="form-group" style="margin-top: 10px">
                                    <input type="checkbox" id="notification_email"
                                           name="notification_email" > {{trans('messages.lblNotificationEmail')}}<span style=" color: red;">★</span>
                                </div>

                                <div class="form-group" style="margin-top: 10px">
                                    <input type="checkbox" id="notification_sms"
                                           name="notification_sms" > {{trans('messages.lblNotificationSms')}}<span style=" color: red;">★</span>
                                </div>


                                <div class="form-group" style="margin-top: 10px">
                                        <input type="checkbox" id="show_contact"
                                               name="show_contact" > {{trans('messages.lblShowContact')}}<span style=" color: red;">★</span>
                                </div>

                                <div class="form-group" style="margin-top: 10px">
                                        <input type="checkbox" id="show_social"
                                               name="show_social" > {{trans('messages.lblShowSocial')}}<span style=" color: red;">★</span>
                                </div>

                                <div >
                                    <label for="avatar">آواتار</label>
                                    <div>
                                        <input id="avatar" name="avatar" extentions="png,bmp,jpg,jpeg" type="file" accept="image/*">
                                    </div>
                                    <small for="avatar">حداکثر اندازه فایل انتخابی باید 100 کیلو بایت باشد و فرمت عکس هم
                                        png,bmp,jpg,jpeg باشد
                                    </small>
                                </div>
                                <br><br>


                            </fieldset>
                        </div>
                    </div>
                    <div class="col-md-12" style="margin: 20px 0px">


                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary" style="width: 25%">
                                        {{trans('messages.lblRegister')}}
                                    </button>
                                </div>
                            </div>
                    </div>
                </form>
                <div><a href="{{ url()->previous() }}">بازگشت</a></div>
            </div>
        </div>


    </div>


@endsection
@section('page-js-script')
    <script type="text/javascript">

        function addSocial() {

            var count = $("#socials > div").length;

            // var inputNameId = "social_name_" + count;
            // var socialNameInput = "نام:"+"<input type=\"text\" name="+inputNameId+" id="+inputNameId+">";

            var selectNameId = "social_name_" + count;

            var socialNameSelect ="<select id="+selectNameId+" name="+selectNameId+">\n" +
                "@foreach(\App\User::Socials as $social)\n" +
                "<option value='{{$social}}'>{{\App\User::getSocialStringByCode($social)}}</option>\n" +
                "@endforeach\n" +
                "</select>";

            var inputAddressId = "social_address_" + count;
            var socialAddressInput = "آدرس:"+"<input type=\"text\" name="+inputAddressId+" id="+inputAddressId+">";

            var removeButtonId = "removeSocialButton";
            var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeSocial(" + count + ");\" class=\"btn btn-primary\">\n" +
                "حذف" +
                "</button>";

            var divSocialId = "div_social_" + count;
            var divSocial = "<div id="+divSocialId+">"+socialNameSelect+socialAddressInput+"</div>";

            $("#socials").append(divSocial);

            if(document.getElementById(removeButtonId) != null)
                document.getElementById(removeButtonId).remove();
            $("#remove_social").append(removeButton);
        }


        function removeSocial(count_id) {

            if(count_id > 0)
            {
                swal({
                    title: 'آیا اطمینان دارید؟',
                    text: 'این عملیات قابل بازگشت نمی باشد',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'بله',
                    cancelButtonText: 'بیخیال'

                }).then((result) => {
                    if(result.value
                    )
                    {
                        document.getElementById("div_social_" + count_id).remove();

                        var count = $("#socials > div").length;

                        var removeButtonId = "removeSocialButton";
                        var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeSocial(" + (count-1) + ");\" class=\"btn btn-primary\">\n" +
                            "حذف" +
                            "</button>";
                        if(document.getElementById(removeButtonId) != null)
                            document.getElementById(removeButtonId).remove();

                        if(count > 1)
                            $("#remove_social").append(removeButton);
                        if(count > 1)
                            count -= 1;

                    }
                })
            }
        }

    </script>
@stop