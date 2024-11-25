@extends('layouts.master_dashboard')
@section('title',  trans('messages.tltBlogEdit'))

@section('content')


    <link href="{{url('quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{url('quill/custom.css')}}" rel="stylesheet">
    <script src="{{url('quill/highlight.pack.js')}}"></script>
    <script src="{{url('quill/quill.js')}}"></script>

    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */
    use Illuminate\Support\Facades\Log;
    $refreshUrl = 'blogs/edit/' . $blog->blog_id . '/' . $blog->blog_guid;
    ?>

    <fieldset>
        <legend>
            {{trans('messages.tltBlogEdit')}}
        </legend>
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
        <form name="blog_form" role="form" method="POST" action="{{ url('/blogs/update') }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <input type="hidden" name="blog_id" id="blog_id" value="{!! $blog->blog_id !!}">
            <input type="hidden" name="blog_guid" id="blog_guid" value="{!! $blog->blog_guid !!}">


            <div class="col-md-12">
                <div class="form-group">
                    <label for="avatar_picture">    {{trans('messages.lblBanner')}}</label>
                    <div>
                        <input id="avatar_picture" name="avatar_picture" extentions="png,bmp,jpg,jpeg" type="file" accept="image/*" class="file-loading">
                    </div>
                    <small for="avatar_picture" style="font-size: 9px;">حداکثر اندازه فایل باید 100 کیلو بایت باشد و
                        فرمت عکس هم شامل png,bmp,jpg,jpeg باشد
                    </small>
                </div>
                @if($blog->hasBanner == 1)
                    <img  style="max-height: 200px;" src="{{URL::to('blogs/getFile/'.$blog->blog_id.'/'.$blog->blog_guid.'/null/'.\App\Tag::TAG_BLOG_AVATAR_DOWNLOAD)}}">
                    <br/>
                    <a type="button" id="download_avatar" title="دانلود تصویر"
                       href="{{URL::to('blogs/getFile/'.$blog->blog_id.'/'.$blog->blog_guid.'/null/'.\App\Tag::TAG_BLOG_AVATAR_DOWNLOAD)}}"
                       target="_blank">{{trans('messages.btnDownload')}}</a>
                    <button type="button" onclick="removeBlogBanner('{{$blog->blog_id}}','{{$blog->blog_guid}}');">حذف</button>

                @else
                    <img   style="max-height: 200px;" src="{{URL::to('images/empty.jpg')}}">
                @endif
            </div>


            <div class="col-md-4">
                <label for="status">{{trans('messages.lblStatus')}}</label>
                <div>
                    <select name="status" id="status" class="form-control">
                        @foreach(\App\Blog::BLOG_STATUSES as $status)
                            @if($status == $blog->status)
                                <option value="{{$status}}" selected>{{\App\Blog::getStatusString($status)}}</option>
                            @else
                                <option value="{{$status}}">{{\App\Blog::getStatusString($status)}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>




            <div class="col-md-4">
                <label for="type">{{trans('messages.lblType')}}</label>
                <div>
                    <select name="type" id="type" class="form-control">
                        @foreach($types as $type)
                            @if($type == $blog->type)
                                <option value="{{$type}}" selected>{{\App\Blog::getTypeString($type)}}</option>
                            @else
                                <option value="{{$type}}">{{\App\Blog::getTypeString($type)}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-md-4">
                <label for="title">    {{trans('messages.lblTitle')}}</label>
                <div class="form-group">
                    <input id="title" type="text" name="title" value="{!! $blog->title !!}" required
                           class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <label for="blog_date_time">    {{trans('messages.lblDate')}} (ex: 2019-03-05)</label>
                <div class="form-group">
                    <input id="blog_date_time" type="text" name="blog_date_time" class="form-control"
                           value="{!! $blog->blog_date_time !!}" required>
                </div>
            </div>
            <div class="col-md-12">
                <label for="description">    {{trans('messages.lblDescription')}}</label>
                <div class="form-group">

                    <input name="description" type="hidden">
                    <div id="editor-container" >{!!$blog->description!!}</div>
                </div>
            </div>

            <div >
                <label for="picture">تصاویر (انتخاب چند تصویر همزمان)</label>
                @foreach($pictures as $picture)
                    <div >
                        <img src="{{URL::to('blogs/getFile/'.$blog->blog_id.'/'.$blog->blog_guid.'/'.$picture->blog_media_id.'/'.\App\Tag::TAG_BLOG_PICTURE_DOWNLOAD)}}" style="width: 25%; height: 25%;">
                        <button type="button" onclick="removeBlogMedia('{{$blog->blog_id}}','{{$blog->blog_guid}}','{{$picture->blog_media_id}}','{{$picture->blog_media_guid}}');">حذف</button>

                    </div>
                @endforeach

                <div>
                    <input id="picture" name="picture[]" multiple extentions="png,bmp,jpg,jpeg" type="file" accept="image/*">
                </div>
                <small for="picture">حداکثر اندازه هر فایل باید 100 کیلو بایت باشد و فرمت عکس هم
                    png,bmp,jpg,jpeg باشد
                </small>
            </div>
            <br><br>

            <div >
                <label for="film">ویدیوها (انتخاب چند ویدیو همزمان)</label>
                @foreach($films as $film)
                    @if($film->link != null)

                        <iframe  width="100%" height="100%" poster="{{url('/images/film_thumb.jpg')}}" src="{!! $film->link !!}" data-video="true"
                                 data-img="{{url('/images/film_thumb.jpg')}}">
                        </iframe>

                    @else
                        <video width="100%" height="100%" poster="{{url('/images/film_thumb.jpg')}}" data-img="{{url('/images/film_thumb.jpg')}}" controls>
                            <source src="{{URL::to('blogs/getFileStream/'.$blog->blog_id.'/'.$blog->blog_guid.'/'.$film->blog_media_id.'/'.\App\Tag::TAG_BLOG_CLIP_DOWNLOAD)}}" type="{{$film->mime_type}}">
                            {{trans('messages.msgErrorNoVideoSupport')}}
                        </video>

                    @endif
                        <button type="button" onclick="removeBlogMedia('{{$blog->blog_id}}','{{$blog->blog_guid}}','{{$film->blog_media_id}}','{{$film->blog_media_guid}}');">حذف</button>

                @endforeach

                <div>
                    <input id="film" name="film[]"  multiple extentions="mp4,ogg,webm" type="file" accept="video/*">
                </div>
                <small for="film">حداکثر اندازه هر فایل باید 20 مگابایت باشد و فرمت ویدیو هم
                    mp4,ogg,webm باشد
                </small>
            </div>
            <br><br>


            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{trans('messages.btnUpdate')}}
                    </button>
                </div>
            </div>
        </form>
        <div><a href="{{ url()->previous() }}">بازگشت</a></div>
    </fieldset>



    <script language="javascript" type="text/javascript">

        let Font = Quill.import('formats/font');
        Font.whitelist = ['IranNastaliq'];
        Quill.register(Font, true);

        var toolbarOptions = [
            ['bold', 'italic'],        // toggled buttons
            ['blockquote'],

            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction

            [{ 'font': ['','IranNastaliq'] }],
            [{ 'align': [] }],

            ['clean']                                         // remove formatting button
        ];

        // var quill = new Quill('#editor-container', {
        //     modules: {
        //         syntax: true,
        //         toolbar: '#toolbar-container'
        //     },
        //     placeholder: '...',
        //     theme: 'snow'
        // });

        var quill = new Quill('#editor-container', {
            modules: {
                syntax: true,
                toolbar: toolbarOptions
            },
            placeholder: '...',
            theme: 'snow'
        });


        {{--$( document ).ready(function() {--}}

            {{--$("#editor-container").html(' {!!  $blog->description!!}' );--}}
            {{--$("#editor-container").text('{!!$blog->description!!}');--}}

        {{--});--}}

        var form = document.querySelector('form');
        form.onsubmit = function() {
            // Populate hidden form on submit
            var about = document.querySelector('input[name=description]');
            // about.value = JSON.stringify(quill.getContents());
            // about.value = JSON.stringify( quill.root.innerHTML);
            about.value = quill.root.innerHTML;
            // about.value = document.querySelector(".ql-editor").innerHTML;
            // about.value = quill.getContents();
            // console.log("Submitted", $(form).serialize(), $(form).serializeArray());

            // No back end to actually submit to!
            // alert('Open the console to see the submit data!')
            // return false;
            return true;
        };

        function removeBlogBanner(blog_id, blog_guid) {

            // event.preventDefault();

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

                    var urlx = '{!! url('/blogs/removeBlogBanner') !!}'+'/'+blog_id+'/'+blog_guid;
                    document.location = urlx;

                }
            })
        }

        function removeBlogMedia(blog_id, blog_guid, media_id, media_guid) {

            // event.preventDefault();

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

                    var urlx = '{!! url('/blogs/removeBlogMedia') !!}'+'/'+blog_id+'/'+blog_guid+'/'+media_id+'/'+media_guid;
                    document.location = urlx;

                }
            })
        }
    </script>

@endsection