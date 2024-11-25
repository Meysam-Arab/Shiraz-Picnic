@extends('layouts.master')
@section('title',  trans('messages.tltTermsOfUse'))
@section('content')
    <div class="row" style="margin-top: 20px; direction: rtl">
        <div class="col-md-3"></div>
        <div class="col-md-6 text-center text-center-mobile wow animated fadeInUp">
            <h3 class="heading no-margin wow animated fadeInUp">{{trans('messages.tltTermsOfUse')}}</h3>
            <div class="details">
                <p class="large muted wow animated fadeInUp no-margin" style="text-align: justify;font-size: large;padding: 0px 15px">
                    {!! trans('messages.txtTermsOfUse') !!}
                </p>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection