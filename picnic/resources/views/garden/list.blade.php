@extends('layouts.master')
@section('title', trans('messages.tltGardens'))

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
    <div id="fh5co-events" data-section="events">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 style="text-align: center">لیست تمامی باغ ها</h3>
                </div>
            </div>
            <div class="row">
                @if(isset($gardens))
                    @foreach($gardens as $garden)
                        <div class="col-md-4" style="float: right">
                            <div class="fh5co-event to-animate-2">
                                <h3>{{$garden->name}}</h3>
                                <img src="{{URL::to('gardens/getFile/'.$garden->garden_id.'/'.$garden->garden_guid.'/1/2.22')}}" class="img-responsive2"
                                     alt="شیراز-پیک نیک">
                                <span class="fh5co-event-meta" style="margin-top: 80px">{{$garden->address}}</span>
                                {{--<p>{{number_format($tour->cost)}} {{trans('messages.lblToman')}}</p>--}}
                                <p><a href="{{url("/gardens/detail/{$garden->garden_id}")}}" class="btn btn-primary btn-outline">جزئیات بیشتر</a></p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection