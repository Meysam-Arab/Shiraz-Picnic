@extends('layouts.master_dashboard')
@section('title',  trans('messages.tltUsersDetails'))

@section('content')
    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */

    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Auth;

    ?>

    <div class="col-md-12">
        <fieldset>
            <legend>{{trans('messages.tltUsersDetails')}}</legend>
        </fieldset>
        <div class="col-md-12">
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
    </div>

    <!-- Latest compiled and minified JavaScript -->
    <div >

        <div>
            <div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-lg-6">
                                {{--<label for="avatar">    {{trans('messages.lblUserAvatar')}}</label>--}}
                            </div>
                            <div class="col-lg-6">
                                @if($user->hasAvatarPicture == 1)
                                    <img src="{{URL::to('users/getFile/'.$user->user_id.'/'.$user->user_guid.'/1.11')}}">
                                    <br/>
                                @else
                                    {{trans('messages.msgNoPictureUploaded')}}
                                    <img src="{{URL::to('images/empty.jpg')}}">
                                @endif
                            </div>
                        </div>
                    </div>
                    <br/>

                {{trans('messages.lblStatus')}}:
                {{\App\User::getStatusStringByCode($user->status)}}
                <br/>
                {{trans('messages.lblType')}}:
                {{\App\User::getTypeStringByCode($user->type)}}
                <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>

                                <div class="col-lg-6 form-group">
                                    <label for="email">    {{trans('messages.lblEmail')}}:</label>

                                       {!! $user->email !!}
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label for="name">{{trans('messages.lblNameAndFamily')}}:</label>

                                        {!! $user->name_family !!}
                                </div>


                            </fieldset>
                        </div>
                    </div>


                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <div class="col-lg-12 form-group">
                                <label for="mobile">{{trans('messages.lblMobile')}}:</label>
                                {!! $user->mobile !!}
                            </div>

                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <div class="col-lg-6 form-group">
                                <label for="postal_code">{{trans('messages.lblNotificationEmail')}}:</label>

                                @if($user->notification_email == 1)
                                    {{trans('messages.lblYes')}}
                                @else
                                    {{trans('messages.lblNo')}}
                                @endif

                            </div>


                            <div class="col-lg-6 form-group">
                                <label for="address">{{trans('messages.lblNotificationSms')}}:</label>

                                @if($user->notification_sms == 1)
                                    {{trans('messages.lblYes')}}
                                @else
                                    {{trans('messages.lblNo')}}
                                @endif

                            </div>


                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <div class="col-lg-12 form-group">
                                <label for="postal_code">{{trans('messages.lblShowContact')}}:</label>

                                @if($user->info->show_contact == 1)
                                    {{trans('messages.lblYes')}}
                                @else
                                    {{trans('messages.lblNo')}}
                                @endif

                            </div>




                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <fieldset>
                            <div class="col-lg-12 form-group">
                                <label for="address">{{trans('messages.lblShowSocial')}}:</label>

                                @if($user->info->show_social == 1)
                                    {{trans('messages.lblYes')}}
                                @else
                                    {{trans('messages.lblNo')}}
                                @endif

                            </div>



                        </fieldset>
                    </div>
                </div>

                <label for="mobile">{{trans('messages.lblSocial')}}:</label>
                @foreach($user->social as $social)
                    <div class="row">

                            <fieldset>

                                <div class="col-lg-6 form-group">
                                    {{trans('messages.lblTitle')}}:{{\App\User::getSocialStringByCode($social->code)}}
                                </div>
                                <div class="col-lg-6 form-group">
                                    {{trans('messages.lblAddress')}}:{{$social->address}}
                                </div>



                            </fieldset>

                    </div>
                @endforeach






                    <div>
                        <div>
                                <a href="{{url()->previous() }}">  {{trans('messages.btnBack')}} </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>


@endsection