@extends('layouts.master_dashboard')
@section('title', trans('messages.tltDashboard'))
@section('content')
    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;
    use  \App\Utilities\JDF;


    ?>
    {{--<fieldset>--}}
        {{--<legend>لیست 10 رزرو آخر</legend>--}}
    {{--</fieldset>--}}
    <div class="col-md-12">
        @if(count($errors) > 0)
            <div class="alert alert-error">
                @foreach($errors as $error)
                    <ul style="color: darkred">
                        <li style="text-align: center" colspan="2">{{$error}}</li>
                    </ul>
                @endforeach
            </div>
            {{ session()->forget('errors')}}
        @endif
        @if(session('messages'))
            <div class="alert alert-warning">
                @foreach (session('messages') as $message)
                    <ul style="color: green">
                        <li style="text-align: center" colspan="2">{{ $message}}</li>
                    </ul>
                @endforeach
            </div>
            {{ session()->forget('messages')}}
        @endif
        @if(isset($messages))
            @foreach ($messages->all() as $message)
                @if (in_array($message->Code, \App\OperationMessage::RedMessages))
                    <tr style="color: darkred;">
                        <th style="text-align: center" colspan="2">{{$message->Text}}</th>

                    </tr>
                @else
                    <tr style="color: green">
                        <th style="text-align: center" colspan="3">{{ $message->Text}}</th>
                    </tr>
                @endif
            @endforeach
        @endif
    </div>

    <div class="fh5co-text">
        <div class="container">
            <div class="row">
                <img src="{{URL::to('images/logo_big_a.png')}}" class="img-responsive" alt="شیراز-پیک نیک"
                     style="margin: 20%;">
            </div>
        </div>
    </div>

@endsection