@extends('layouts.new1master')


@section('title', $blog->title)
@section('description')
    {{\App\Utilities\Utility::GetFirstNChar($blog->description, 160)}}
@endsection



@section('content')
    <link href="{{URL::to('css/gallery/fotorama.css')}}" rel="stylesheet">
    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */

    use Illuminate\Support\Facades\Log;
    use Morilog\Jalali\jDate;
    use Carbon\Carbon;


    ?>

    <link href="{{url('quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{url('quill/custom.css')}}" rel="stylesheet">

    <style>
        .fotorama__wrap {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>

    <div class="container direction" style="margin-top: 50px">
        <div class="row">
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
            </div>


            <div class="col-md-12" style="text-align: center !important;">
                <h2>
                    {!! $blog->title !!}
                </h2>
            </div>
            <div class="col-md-12">
                <h5 style="text-align: center !important;">
                    <?php
                    $carbon = new Carbon($blog->blog_date_time);
                    $year = $carbon->year;
                    $month = $carbon->month;
                    $day = $carbon->day;
                    $jdf = new \App\Utilities\JDF();
                    $date = $jdf->gregorian_to_jalali($year, $month, $day, '/');
                    ?>
                    {!! $date!!}


                </h5>

            </div>
            <div class="col-md-12" style="margin-top: 30px">
                @if($blog->hasCoverPicture)
                    <img src="{{URL::to('blogs/getFile/'.$blog->blog_id.'/'.$blog->blog_guid.'/null/'.\App\Tag::TAG_BLOG_AVATAR_DOWNLOAD)}}"
                         style="width: 100%; display: block; margin-left: auto; margin-right: auto; min-height: 200px; max-height: 500px;"
                         class="center" ;>

                    @endif

                <br/>
            </div>

            <div class="col-md-12" style="margin-top: 30px;direction: rtl;text-align: justify;font-size: large;">
                {!! $blog->description !!}
            </div>

            <div class="col-md-12" style="margin: 20px 0px">
                @if(count($pictures)>0 || count($films)>0)
                    <div class="row" style="display: block;margin: 0px auto;">
                        <div class="col-md-12">
                            <div class="fotorama about-image" data-allowfullscreen="native" data-nav="thumbs">
                                @foreach($pictures as $picture)
                                    <img src="{{URL::to('blogs/getFile/'.$blog->blog_id.'/'.$blog->blog_guid.'/'.$picture->blog_media_id.'/'.\App\Tag::TAG_BLOG_PICTURE_DOWNLOAD)}}"
                                         style="width: 100%; display: block; margin-left: auto; margin-right: auto; min-height: 200px; max-height: 500px;"
                                         class="center" ;>
                                @endforeach
                                @foreach($films as $video)
                                    @if($video->link != null)
                                        {{--<iframe  width="100%" height="100%" poster="{{url('/images/film_thumb.jpg')}}" src="{!! $video->link !!}" data-video="true"--}}
                                        {{--data-img="{{url('/images/film_thumb.jpg')}}">--}}
                                        {{--</iframe>--}}
                                        {{--<a href="{!! $video->link !!}"--}}
                                        {{--data-video="true"--}}

        <br>                              {{--data-img="{{url('/images/film_thumb.jpg')}}">--}}
        {{--</a>--}}
        <a href="{!! $video->link !!}"
           data-video="true"
           data-img="{{url('/images/film_thumb.jpg')}}">
            <img src="{{url('/images/film_thumb.jpg')}}">
        </a>
        @else
            {{--<video width="100%" height="100%" poster="{{url('/images/film_thumb.jpg')}}" data-img="{{url('/images/film_thumb.jpg')}}" controls>--}}
            {{--<source src="{{URL::to('blogs/getFileStream/'.$blog->blog_id.'/'.$blog->blog_guid.'/'.$video->blog_media_id.'/'.\App\Tag::TAG_BLOG_CLIP_DOWNLOAD)}}"  type="{{$video->mime_type}}">--}}
            {{--{{trans('messages.msgErrorNoVideoSupport')}}--}}
            {{--</video>--}}
            <a href="{{URL::to('blogs/getFileStream/'.$blog->blog_id.'/'.$blog->blog_guid.'/'.$video->blog_media_id.'/'.\App\Tag::TAG_BLOG_CLIP_DOWNLOAD)}}"
               data-video="true"
               data-img="{{url('/images/film_thumb.jpg')}}">
                <img src="{{url('/images/film_thumb.jpg')}}">
            </a>
        @endif
        @endforeach
    </div>
    <hr>

    <br>
    </div>
    </div>
    @endif
    </div>

    <div class="col-md-12" style="margin: 20px 0px">
        <a href="{{url()->previous()}}" class="btn btn-primary"
           style="max-width:30%;display: block;margin-right: auto;margin-left: auto">  {{trans('messages.btnBack')}} </a>
    </div>
    </div>
    <br>
    </div>

    <script src="{{URL::to('js/gallery/fotorama.js')}}"></script>
@endsection