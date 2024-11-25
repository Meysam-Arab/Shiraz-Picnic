@extends('layouts.new1master')

@section('title', 'کاتالوگ')
@section('description')
    {{'شیراز پیک نیک محلی برای اجاره باغ های عمومی و خصوصی و رزرو تورهای تفریحی و گردشگری'}}
@endsection

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

            @if(count($lastestNews) > 0)
                <div class="row post-contents" style="margin-top: 50px">
                    <h1 style="width: 100%;text-align: center">اطلاع رسانی</h1>
                </div>
                <section class="blog_categorie_area">
                    <div class="container">
                        <div class="row">
                            @foreach($lastestNews as $lastestNew)
                                <?php
                                $date = Carbon::parse($lastestNew->blog_date_time);
                                $day = \Morilog\Jalali\jDate::forge($date->getTimestamp())->format('%d'); // دی 02، 1391
                                $month = \Morilog\Jalali\jDate::forge($date->getTimestamp())->format('%B');

                                $temp_desc = strip_tags($lastestNew->description);
                                if (mb_strlen($temp_desc, "utf8") > 100) {
                                    $temp_desc = substr($temp_desc, 0, 100) . ' ... ';
                                }
                                ?>
                                <div class="col-lg-4">
                                    <div class="categories_post">
                                        @if(\App\Utilities\Utility::isFileExist(\App\Tag::TAG_BLOG_AVATAR,\App\Blog::BLOG_BANNER_FILE_NAME,$lastestNew->blog_id))
                                            <img src="{{URL::to('blogs/getFile/'.$lastestNew->blog_id.'/'.$lastestNew->blog_guid.'/null/'.\App\Tag::TAG_BLOG_AVATAR_DOWNLOAD)}}"
                                                 class="img-responsive2" style="min-height: 200px;max-height: 200px;"
                                                 alt="{{$lastestNew->title}}"/>
                                        @else
                                            <img src="{{URL::to('images/empty.jpg')}}"
                                                 class="img-responsive2" style="min-height: 200px;max-height: 200px;"
                                                 alt="{{$lastestNew->title}}">
                                        @endif
                                        <div class="categories_details">
                                            <div class="categories_text">
                                                <a href="{{url("/blogs/detail/{$lastestNew->blog_id}")}}">
                                                    <h5>{{$lastestNew->title}}</h5>

                                                <div class="border_line"></div>
                                                <p>{{$temp_desc}}</p>
                                                <div class="date">
                                                    <div class="day">{{$day}}</div>
                                                    <div class="month">{{$month}}</div>
                                                </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <div class="row">
                    <div class="col-md-12"></div>
                    <a href="{{url('/blogs/list/0')}}" class="btn btn-primary btn-down-01"
                       style="margin: 0px auto 30px auto;display: block;max-width: 60%;">نمایش تمامی مطالب</a>
                </div>

            @endif
            @if(count($lastestInstructions) > 0)
                <div class="row post-contents" style="margin-top: 50px">
                    <h1 style="width: 100%;text-align: center">آموزنده</h1>
                </div>
                <section class="blog_categorie_area">
                    <div class="container">
                        <div class="row">
                            @foreach($lastestInstructions as $lastestNew)
                                <?php
                                $date = Carbon::parse($lastestNew->blog_date_time);
                                $day = \Morilog\Jalali\jDate::forge($date->getTimestamp())->format('%d'); // دی 02، 1391
                                $month = \Morilog\Jalali\jDate::forge($date->getTimestamp())->format('%B');

                                $temp_desc = strip_tags($lastestNew->description);
                                if (mb_strlen($temp_desc, "utf8") > 100) {
                                    $temp_desc = substr($temp_desc, 0, 100) . ' ... ';
                                }
                                ?>
                                <div class="col-lg-4">
                                    <div class="categories_post">
                                        <img src="{{URL::to('blogs/getFile/'.$lastestNew->blog_id.'/'.$lastestNew->blog_guid.'/null/'.\App\Tag::TAG_BLOG_AVATAR_DOWNLOAD)}}"
                                             class="img-responsive2" style="min-height: 200px;max-height: 200px;"
                                             alt="{{$lastestNew->title}}"/>
                                        <div class="categories_details">
                                            <div class="categories_text">
                                                <a href="{{url("/blogs/detail/{$lastestNew->blog_id}")}}">
                                                    <h5>{{$lastestNew->title}}</h5>

                                                <div class="border_line"></div>
                                                <p>{{$temp_desc}}</p>
                                                <div class="date">
                                                    <div class="day">{{$day}}</div>
                                                    <div class="month">{{$month}}</div>
                                                </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                <div class="row">
                    <div class="col-md-12"></div>
                    <a href="{{url('/blogs/list/1')}}" class="btn btn-primary btn-down-01"
                       style="margin: 0px auto 30px auto;display: block;max-width: 60%;">نمایش تمامی مطالب</a>
                </div>
            @endif
        </div>
    </section>
    <script>
        $(window).load(function () {
            $('.post-module').hover(function () {
                $(this).find('.description').stop().animate({
                    height: "toggle",
                    opacity: "toggle"
                }, 300);
            });
        });
    </script>
@endsection