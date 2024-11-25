@extends('layouts.master_dashboard')
@section('title',  trans('messages.tltFeedBackDetails'))

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

    <style>
        label {
            color: #d141f0;
        }
    </style>
    <div>

        <div class="direction">
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

            <div class="col-md-12">
                <fieldset>
                    <legend>
                        <h5>
                            {{trans('messages.tltFeedBackDetails')}}
                        </h5>
                        <br/>
                        <div>


                            <div>
                                <label for="title">    {{trans('messages.lblTitle')}}</label>

                                <div>
                                    {!! $feedback->title !!}
                                </div>
                            </div>
                            <br>

                            <div>
                                <label for="description">    {{trans('messages.lblDescription')}}</label>

                                <div>
                                    {!! $feedback->description !!}
                                </div>
                            </div>
                            <br>
                            <div>
                                <label for="name_and_family">    {{trans('messages.lblNameAndFamily')}}</label>

                                <div>
                                    {!! $feedback->name_and_family !!}
                                </div>
                            </div>
                            <br>
                            <div>
                                <label for="tel">    {{trans('messages.lblTel')}}</label>

                                <div>
                                    {!! $feedback->tel !!}
                                </div>
                            </div>
                            <br>
                            <div>
                                <label for="email">    {{trans('messages.lblEmail')}}</label>

                                <div>
                                    {!! $feedback->email !!}
                                </div>
                            </div>
                            <br>

                            <div>
                                <div>
                                    <a href="{{url("/feedbacks/index")}}">  {{trans('messages.btnBack')}} </a>
                                </div>
                            </div>

                        </div>
                    </legend>
                </fieldset>
            </div>
        </div>


    </div>


@endsection