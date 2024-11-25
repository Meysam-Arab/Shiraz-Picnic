@extends('layouts.master_dashboard')
@section('title',  'ایجاد بلاگ جدید')

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

    ?>
    <div class="direction">
        <div class="col-md-12">
            @if(count($errors) > 0)
                <div class="alert alert-danger">
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
        <div class="col-md-12">
            <fieldset>
                <legend>
                    <h5>
                        ایجاد بلاگ جدید
                    </h5>
                </legend>
                <form name="blog_form" role="form" method="POST" action="{{ url('/blogs/store') }}"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="status">@lang('messages.lblStatus')</label>
                        <div>
                            <select name="status" id="status" class="form-control">
                                @foreach(\App\Blog::BLOG_STATUSES as $status)
                                    <option value="{{$status}}">{{\App\Blog::getStatusString($status)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type">نوع</label>
                        <div>
                            <select name="type" id="type" class="form-control">
                                @foreach($types as $type)
                                <option value="{{$type}}">{{\App\Blog::getTypeString($type)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div >
                        <label for="avatar_picture">بنر</label><span style=" color: red;">★</span>
                        <div>
                            <input id="avatar_picture" name="avatar_picture" extentions="png,bmp,jpg,jpeg" type="file" accept="image/*">
                        </div>
                        <small for="avatar_picture">حداکثر اندازه فایل انتخابی باید 100 کیلو بایت باشد و فرمت عکس هم
                            png,bmp,jpg,jpeg باشد
                        </small>
                    </div>
                    <br><br>

                    <div >
                        <label for="picture">تصاویر (انتخاب چند تصویر همزمان)</label>
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
                        <div>
                            <input id="film" name="film[]" multiple extentions="mp4,ogg,webm" type="file" accept="video/*">
                        </div>
                        <small for="film">حداکثر اندازه هر فایل باید 20 مگابایت باشد و فرمت ویدیو هم
                            mp4,ogg,webm باشد
                        </small>
                    </div>
                    <br><br>



                    <div class="form-group">
                        <label for="title">عنوان</label><span style=" color: red;">★</span>
                        <div>
                            <input id="title" type="text" name="title"  class="form-control" value="{{ old('title') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">متن</label><span style=" color: red;">★</span>
                        <div>


                            <input name="description" type="hidden">
                            <div id="editor-container" >{!!old('description')!!}</div>


                        </div>
                    </div>
                    <div class="form-group">
                        <label for="blog_date_time">تاریخ (ex: 2019-03-05 21:00:00)</label><span style=" color: red;">★</span>
                        <div>
                            <input id="blog_date_time" type="text" name="blog_date_time"  class="form-control"
                                   value="{{ old('blog_date_time') }}"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                ثبت
                            </button>
                        </div>
                    </div>
                </form>
                <div><a href="{{ url()->previous() }}">بازگشت</a></div>
            </fieldset>
        </div>
    </div>
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

        var quill = new Quill('#editor-container', {
            modules: {
                syntax: true,
                toolbar: toolbarOptions
            },
            placeholder: '...',
            theme: 'snow'
        });


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

    </script>


@endsection