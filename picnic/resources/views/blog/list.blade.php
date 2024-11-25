@extends('layouts.master')
@section('title', 'لیست بلاگ ها')

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


    <section id="blog">
        <div class="container">
            <div class="section-header">
                {{--<h2 class="section-title text-center wow fadeInDown animated"--}}
                {{--style="visibility: visible; animation-name: fadeInDown;">{{trans('messages.tlt_blog_list')}}</h2>--}}
                {{--<p class="text-center wow fadeInDown animated" style="visibility: visible; animation-name: fadeInDown;">--}}
                {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut <br>--}}
                {{--et dolore magna aliqua. Ut enim ad minim veniam</p>--}}
                {{--<p>--}}
                @if(count($errors) > 0)
                    <div class="alert-error alert">
                        @foreach($errors as $error)
                            <ul style="color: darkred">
                                <li style="text-align: center" colspan="2">{{$error}}</li>
                            </ul>
                        @endforeach
                    </div>
                    {{ session()->forget('errors')}}
                @endif

                @if(session('messages'))
                    <div class="alert-warning alert">
                        @foreach (session('messages') as $message)
                            <ul style="color: green">
                                <li style="text-align: center" colspan="2">{{ $message}}</li>
                            </ul>
                        @endforeach
                    </div>
                    {{ session()->forget('messages')}}
                @endif

            </div>
            </p>

            <div class="row">
                <div class="col-md-12">

                    @if(isset($blogs))
                        @foreach($blogs as $blog)
                            <?php

                                $carbon = new Carbon($blog->blog_date_time);
                                $year = $carbon->year;
                                $month = $carbon->month;
                                $day = $carbon->day;
                                $jdf = new \App\Utilities\JDF();
                                $date = $jdf->gregorian_to_jalali($year, $month, $day, '/');

                            ?>

                            <div class="col-md-4" style="margin-top: 10px">
                                <a target="_blank" href="{{url("/blogs/detail/{$blog->blog_id}")}}">
                                    <div class="text-center item">
                                        <div class="blog-post blog-large">
                                            <article>
                                                <header class="entry-header">
                                                    <div class="entry-thumbnail">
                                                        @if($blog->hasCoverPicture)
                                                        <img src="{{URL::to('blogs/getFile/'.$blog->blog_id.'/'.$blog->blog_guid.'/null/5.33')}}"
                                                             class="img-responsive2" style="min-height: 200px;max-height: 200px;"
                                                             alt="شیراز-پیک نیک">
                                                        @else
                                                            <img src="{{URL::to('images/empty.jpg')}}"
                                                                 class="img-responsive2" style="min-height: 200px;max-height: 200px;"
                                                                 alt="شیراز-پیک نیک">
                                                        @endif

                                                    </div>
                                                    {{--@if($blog->type == 0)--}}
                                                    {{--<td>{{trans('messages.txt_blog_type_news')}}</td>--}}
                                                    {{--@else--}}
                                                    {{--<td>{{trans('messages.txt_blog_type_instructions')}}</td>--}}
                                                    {{--@endif--}}
                                                    <div class="entry-date" style="font-size: large">{{$date}}</div>
                                                    <h2 class="entry-title" style="font-weight: 600">{!!  $blog->title !!}
                                                    </h2>
                                                </header>

                                                <div class="entry-content">
                                                    <?php
//                                                    $temp = strip_tags($blog->description);
//                                                    if (mb_strlen($temp, "utf8") > 300) {
//                                                        $temp = substr($temp, 0, 300).' ... ';
//                                                    }
//                                                    else
//                                                    {
//                                                        $temp = $temp.' '.str_repeat ( "&nbsp;" , 300 );
//                                                    }
                                                    ?>
                                                    {{--<p style="color: #5a5a5a">{!! $temp !!}</p>--}}
                                                    <a target="_blank" class="btn btn-primary"
                                                       href="{{url("/blogs/detail/{$blog->blog_id}")}}">{{trans('messages.btnDetails')}}</a>
                                                </div>


                                            </article>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection