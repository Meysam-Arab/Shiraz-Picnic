@extends('layouts.master_dashboard')
@section('title',  trans('messages.lblEditUserProfile'))

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
    <div class="ms2-box">
        <div class="col-md-11 direction">
            <fieldset>
                <legend>
                    <h5 class="mb-0">
                        {{trans('messages.lblEditUserProfile')}}
                    </h5>
                </legend>
                <div>
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
                                <li colspan="2">{{ $message}}</li>
                            </ul>
                        @endforeach
                            </div>
                        {{ session()->forget('messages')}}
                    @endif
                </div>
                <div>
                    <form name="edit_form" method="POST" action="{{ url('/users/update') }}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{!! $user->user_id !!}">
                        <input type="hidden" name="user_guid" value="{!! $user->user_guid !!}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-lg-6">
                                    <label for="avatar">    {{trans('messages.lblUserAvatar')}}</label>
                                    <div>
                                        <input tag="1.1" id="avatar" name="avatar" extentions="png,bmp,jpg,jpeg"
                                               id="input-img" type="file" accept="image/*" class="file-loading">
                                    </div>
                                    <small for="avatar">حداکثر اندازه فایل باید 100 کیلو بایت باشد و فرمت عکس هم شامل
                                        png,bmp,jpg,jpeg باشد
                                    </small>
                                </div>
                                <div class="col-lg-6">
                                    @if($user->hasAvatar == 1)
                                        <img src="{{URL::to('users/getFile/'.$user->user_id.'/'.$user->user_guid.'/1.11')}}">
                                        <br/>
                                        <a type="button" id="download_avatar" tag="1.11" title="دانلود عکس پرسنلی"
                                           href="{{URL::to('users/downloadFile/'.$user->user_id.'/'.$user->user_guid.'/1.11')}}"
                                           target="_blank">{{trans('messages.btnDownload')}}</a>
                                        <a type="button" id="delete_avatar" tag="-1.1" title="حذف عکس پرسنلی" onclick="removeUserAvatar('{{$user->user_id}}','{{$user->user_guid}}')"

                                           >{{trans('messages.btnDelete')}}</a>
                                    @else
                                        <img style="max-height: 215px;max-width: 200px" src="{{URL::to('images/empty.jpg')}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br/>


                        @if(Auth()->user()->type == \App\User::TypeAdmin ||
                        (Auth()->user()->type == \App\User::TypeOperator &&
                        $user->type != \App\User::TypeOperator &&
                        $user->type != \App\User::TypeAdmin))

                            <label class="float"> {{trans('messages.lblStatus')}}</label><span style=" color: red;">★</span>
                            <select id="status" name="status">
                                @foreach(\App\User::Statuses as $status)
                                    @if($status == $user->status)
                                        <option value='{{$status}}' selected>{{\App\User::getStatusStringByCode($status)}}</option>
                                    @else
                                        <option value='{{$status}}'>{{\App\User::getStatusStringByCode($status)}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="col-md-6" style="margin-top:10px;margin-bottom: 30px;">
                                <label class="float"> {{trans('messages.lblType')}}:</label><span style=" color: red;">★</span>
                                <select id="type" name="type">
                                    @foreach(\App\User::Types as $type)
                                        @if($type == $user->type)
                                            <option value='{{$type}}' selected>{{\App\User::getTypeStringByCode($type)}}</option>
                                        @else
                                            <option value='{{$type}}'>{{\App\User::getTypeStringByCode($type)}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @else
                            {{\App\User::getStatusStringByCode($user->status)}}
                            {{\App\User::getTypeStringByCode($user->type)}}
                        @endif


                        <br/>


                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>رمز عبور</legend>
                                    <div class="col-lg-4 form-group">
                                        <label for="old_password">    {{trans('messages.lblOldPassword')}}</label>

                                        <div>
                                            <input id="old_password" type="password" name="old_password" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="password">    {{trans('messages.lblNewPassword')}}</label>

                                        <div>
                                            <input id="password" type="password" name="password" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="password_confirmation">    {{trans('messages.lblPasswordConfirmation')}}</label>

                                        <div>
                                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>اطلاعات پایه</legend>
                                    <div class="col-lg-4 form-group">
                                        <label for="email">    {{trans('messages.lblEmail')}}</label>

                                        <div>
                                            <input id="email" type="text" name="email" value="{!! $user->email !!}" required
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="name_family">{{trans('messages.lblNameAndFamily')}}</label>

                                        <div>
                                            <input id="name_family" type="text"  class="form-control" name="name_family" value="{!! $user->name_family !!}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label for="mobile">{{trans('messages.lblMobile')}}</label>

                                        <div>
                                            <input id="mobile" type="tel"  class="form-control" name="mobile" value="{!! $user->mobile !!}" required>
                                        </div>
                                    </div>

                                </fieldset>
                            </div>
                        </div>

                        <label class="float"> شبکه های اجتماعی:</label>
                        <div id="socials">


                            <?php
                            $socialCount = -1;
                            ?>
                            @if(count($user->social) > 0)
                                @foreach($user->social as $user_social)
                                    <?php
                                    $socialCount++;
                                    ?>
                                    <div id="div_social_{{$socialCount}}">
                                        <select id="social_name_{{$socialCount}}" name="social_name_{{$socialCount}}">
                                            @if($socialCount == 0)
                                                <option value=''>انتخاب کنید</option>
                                            @endif
                                            @foreach(\App\User::Socials as $social)
                                                @if($social == $user_social->code)
                                                    <option value='{{$social}}'  selected="selected">{{\App\User::getSocialStringByCode($social)}}</option>
                                                @else
                                                    <option value='{{$social}}'>{{\App\User::getSocialStringByCode($social)}}</option>
                                                @endif
                                            @endforeach


                                        </select>

                                        آدرس: <input id="social_address_{{$socialCount}}" name="social_address_{{$socialCount}}" value="{{$user_social->address}}" type="text">
                                    </div>

                                @endforeach
                            @else
                                <div id="div_social_0">
                                    نام:<select id="social_name_0" name="social_name_0">
                                        <option value=''>انتخاب کنید</option>

                                        @foreach(\App\User::Socials as $social)
                                            <option value='{{$social}}'>{{\App\Tour::getSocialStringByCode($social)}}</option>
                                        @endforeach

                                    </select>
                                    آدرس: <input id="social_address_0" name="social_address_0" type="text">
                                </div>
                            @endif


                        </div>
                        <div id="remove_social">
                            @if($socialCount > 0)
                                <button  type="button" id="removeSocialButton"  onclick="removeSocial('{{$socialCount}}');" class="btn btn-primary"> حذف   </button>
                            @endif
                        </div>
                        <button  type="button" id="add_social" onclick="addSocial()">افزودن</button>
                        <br> <br>


                        <div>

                            <div>

                                <div>
                                    <label>

                                        @if($user->notification_email == 1)
                                            <input type="checkbox" id="notification_email" name="notification_email"
                                                   checked>
                                        @else
                                            <input type="checkbox" id="notification_email" name="notification_email">

                                        @endif

                                        {{trans('messages.lblNotificationEmail')}}

                                    </label>
                                </div>


                            </div>
                        </div>
                        <div>

                            <div>


                                <div>
                                    <label>

                                        @if($user->notification_sms == 1)
                                            <input type="checkbox" id="notification_sms" name="notification_sms"
                                                   checked>
                                        @else
                                            <input type="checkbox" id="notification_sms" name="notification_sms">

                                        @endif

                                        {{trans('messages.lblNotificationSms')}}

                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>

                            <div>


                                <div>
                                    <label>

                                        @if($user->info->show_contact == 1)
                                            <input type="checkbox" id="show_contact" name="show_contact"
                                                   checked>
                                        @else
                                            <input type="checkbox" id="show_contact" name="show_contact">

                                        @endif

                                        {{trans('messages.lblShowContact')}}

                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>

                            <div>


                                <div>
                                    <label>

                                        @if($user->info->show_social == 1)
                                            <input type="checkbox" id="show_social" name="show_social"
                                                   checked>
                                        @else
                                            <input type="checkbox" id="show_social" name="show_social">

                                        @endif

                                        {{trans('messages.lblShowSocial')}}

                                    </label>
                                </div>
                            </div>
                        </div>


                        <div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    {{trans('messages.btnUpdate')}}
                                </button>
                            </div>
                        </div>
                    </form>
                    <div><a href="{{ url()->previous() }}">بازگشت</a></div>
                </div>
            </fieldset>
        </div>
    </div>


@endsection
@section('page-js-script')
    <script type="text/javascript">

        function addSocial() {

            var count = $("#socials > div").length;


            var selectNameId = "social_name_" + count;
            var socialNameSelect ="<select id="+selectNameId+" name="+selectNameId+">\n" +
                "@foreach(\App\Tour::Socials as $social)\n" +
                "<option value='{{$social}}'>{{\App\Tour::getSocialStringByCode($social)}}</option>\n" +
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

        function removeUserAvatar(user_id, user_guid) {


            swal({
                title: 'آیا اطمینان دارید؟',
                text: 'این عملیات بازگشت ناپذیر می باشد!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'خیر'

            }).then((result) => {
                if(result.value
                )
                {
                    // alert('ddd: user id'+user_id);

                    var urlx = '{!! url('/users/removeUserAvatar') !!}'+'/'+user_id+'/'+user_guid;
                    document.location = urlx;
                    return true;

                }

            })
            return false;
        }


    </script>
@stop