@extends('layouts.master_dashboard')
@section('title',  'ویرایش ویژگی')

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

    <div class="table-header">
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
    <div class="table-header">
        @if(isset($messages))
            @foreach ($messages->all() as $message)
                @if (in_array($message->Code, \App\OperationMessage::RedMessages))
                    <tr style="color: darkred;">
                        <th style="text-align: center" colspan="2">{{$message->Text}}</th>

                    </tr>
                @else
                    <tr style="color: green">
                        <th style="text-align: center" colspan="2">{{ $message->Text}}</th>
                    </tr>
                @endif
            @endforeach
        @endif
    </div>


    <div style="margin-top: 40px; direction: rtl">
        <form name="edit_form" role="form" method="POST"
              action="{{ url('/features/update') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="feature_id" value="{!! $feature->feature_id !!}">
            <input type="hidden" name="feature_guid" value="{!! $feature->feature_guid !!}">

            نام:<br>
            <input type="text" name="name" value="{{$feature->name}}">
            <br>

            توضیحات:<br>
            <textarea name="description">{{$feature->description}}</textarea>
            <br>

            <div >
                <label for="icon">آیکون</label>
                <div >

                        <img
                                src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}" style="width: 10%; height: 10%;">


                </div>
                <br>
                <div>
                    <input id="icon" name="icon" extentions="svg" type="file" accept="image/svg+xml">
                </div>
                <small for="avatar">حداکثر اندازه فایل باید 100 کیلو بایت باشد و فرمت عکس هم
                    svg باشد
                </small>
            </div>

            <br>
            <input type="submit" value="ثبت">
        </form>

        <div><a href="{{ url()->previous() }}">بازگشت</a></div>




    </div>
@endsection