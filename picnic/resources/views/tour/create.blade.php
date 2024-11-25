@extends('layouts.master_dashboard')
@section('title',  'ایجاد تور جدید')

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

    $feature_counter = 0;

    ?>

    <link rel="stylesheet" href="{{url('/assets-leaflet/css/leaflet.css')}}" />
    <script src="{{url('/assets-leaflet/js/leaflet.js')}}"></script>

<style>
    table, th, td {
        border: 1px solid black;
    }
</style>



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


    <form name="create_form" role="form" method="POST"
          action="{{ url('/tours/store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div id="start_point">
            <input type="hidden" id="start_point_lat" name="start_point_lat">
            <input type="hidden" id="start_point_lon" name="start_point_lon">
        </div>

        <div id="end_point">
            <input type="hidden" id="end_point_lat" name="end_point_lat">
            <input type="hidden" id="end_point_lon" name="end_point_lon">
        </div>



    @if(Auth::user()->type == \App\User::TypeAdmin ||
                  Auth::user()->type == \App\User::TypeOperator)
    <div class="row" style="direction: rtl">
        <div class="col-md-12">
            <div class="col-md-6" style="margin-top:10px;margin-bottom: 30px;">
                <label class="float"> وضعیت:</label><span style=" color: red;">★</span>


                        <select id="status" name="status">
                            <option value='{{\App\Tour::StatusActive}}'>فعال</option>
                            <option value='{{\App\Tour::StatusNonActive}}'>غیر فعال</option>
                            <option value='{{\App\Tour::StatusWaitForCheck}}'>در انتظار تایید</option>
                            <option value='{{\App\Tour::StatusDisapproved}}'>رد شده</option>
                            <option value='{{\App\Tour::StatusCanceled}}'>کنسل شده</option>

                        </select>


            </div>
        </div>
    </div>



    <div class="row" style="direction: rtl">
        <div class="col-md-12">
            <div class="col-md-6" style="margin-top:10px;margin-bottom: 30px;">
                <label class="float"> صاحب تور:</label><span style=" color: red;">★</span>
                <select id="owner" name="owner">
                    @foreach($owners as $owner)
                        <option value='{{$owner->user_id}}'>{{$owner->name_family}}</option>
                    @endforeach

                </select>
            </div>
        </div>
    </div>

    <div class="row" style="direction: rtl">
        <div class="col-md-12">
            <div class="col-md-6" style="margin-top:10px;margin-bottom: 30px;">
                <label class="float"> درصد سهم سایت:</label><span style=" color: red;">★</span>
                <input id="site_share" name="site_share" type="number" value="0.5" step="0.1">
            </div>
        </div>
    </div>





    @endif

    <div class="row" style="direction: rtl">
        <div class="col-md-12">
            <div class="col-md-6" style="margin-top:10px;margin-bottom: 30px;">
                <label class="float"> لیدر/لیدرها:</label>
                <div id="leaders">
                    <div id="div_leader_0">
                        <select id="leader_0" name="leader_0">
                            <option value=''>انتخاب کنید</option>
                            @foreach($leaders as $leader)
                                <option value='{{$leader->user_id}}'>{{$leader->name_family}}</option>
                            @endforeach

                        </select>

                    </div>

                </div>
                <div id="remove_leader"></div>
                <button  type="button" id="add_leader" onclick="addLeader()">افزودن</button>
            </div>

        </div>
    </div>



    <div style="margin-top: 40px; direction: rtl">


            عنوان:<span style=" color: red;">★</span><br>
            <input type="text" id="title" name="title">
            <br> <br>

            توضیحات:<span style=" color: red;">★</span><br>
            {{--<textarea id="description" name="description"></textarea>--}}
            <input name="description" type="hidden">
            <div id="tour_description_editor_container" >{!!old('description')!!}</div>
            <br> <br>

            ظرفیت کل:<span style=" color: red;">★</span><br>
            <input type="number" id="total_capacity" name="total_capacity" value="20" step="1">
            <br> <br>

            ظرفیت باقی مانده:<span style=" color: red;">★</span><br>
            <input type="number" id="remaining_capacity" name="remaining_capacity" value="20" step="1">
            <br> <br>

            تاریخ شروع: ex: 2019-03-06 21:00:00<span style=" color: red;">★</span><br>
            <input type="text" id="start_date_time" name="start_date_time">
            <br> <br>

            تاریخ پایان: ex: 2019-03-06 21:00:00<span style=" color: red;">★</span><br>
            <input type="text" id="end_date_time" name="end_date_time">
            <br> <br>

            مهلت ثبت نام: ex: 2019-03-05 21:00:00<span style=" color: red;">★</span><br>
            <input type="text" id="deadline_date_time" name="deadline_date_time">
            <br> <br>

            هزینه:<span style=" color: red;">★</span><br>
            <input type="number" id="cost" name="cost" value="0" step="500"> تومان
            <br> <br>

            هزینه خط خورده:<br>
            <input type="number" id="stroked_cost" name="stroked_cost" value="0" step="500"> تومان
            <br> <br>

            تلفن هماهنگی:<span style=" color: red;">★</span><br>
            <input type="text" id="coordination_tel" name="coordination_tel">
            <br> <br>

            نام شرکت:<br>
            <input type="text" id="company" name="company">
            <br> <br>

            قوانین:<br>
            <textarea id="regulations" name="regulations"></textarea>
            <br> <br>

            حداقل سن:<span style=" color: red;">★</span><br>
            <input type="number" id="minimum_age" name="minimum_age" value="2" step="1">
            <br> <br>

            حداکثر سن:<span style=" color: red;">★</span><br>
            <input type="number" id="maximum_age" name="maximum_age" value="60" step="1">
            <br> <br>


            جنسیت مجاز:<span style=" color: red;">★</span><br>
            <select id="gender" name="gender">
                @foreach(\App\Tour::Genders as $gender)
                        <option value='{{$gender}}'>{{\App\Tour::getGenderStringByCode($gender)}}</option>
                @endforeach

            </select>
            <br> <br>

            درجه سختی:<span style=" color: red;">★</span><br>
            <select id="hardship_level" name="hardship_level">
                @foreach(\App\Tour::HardshipLevels as $hardshipLevel)
                        <option value='{{$hardshipLevel}}'>{{\App\Tour::getHardshipLevelStringByCode($hardshipLevel)}}</option>
                @endforeach
            </select>
            <br> <br>

            <label class="float"> شبکه های اجتماعی:</label>
            <div id="socials">
                <div id="div_social_0">
                    {{--نام:<input id="social_name_0" name="social_name_0" type="text">--}}
                    نام:<select id="social_name_0" name="social_name_0">
                        <option value=''>انتخاب کنید</option>
                        @foreach(\App\Tour::Socials as $social)
                            <option value='{{$social}}'>{{\App\Tour::getSocialStringByCode($social)}}</option>
                        @endforeach

                    </select>
                   آدرس: <input id="social_address_0" name="social_address_0" type="text">
                </div>

            </div>
            <div id="remove_social"></div>
            <button  type="button" id="add_social" onclick="addSocial()">افزودن</button>
            <br> <br>




        <label class="float"> تخفیف ها:</label>
        <div id="discounts">
            <div id="div_discount_0">

                کد: <input id="discount_code_0" name="discount_code_0" type="text">
                ظرفیت: <input id="discount_capacity_0" name="discount_capacity_0" type="number"  value="1" step="1">
                ظرفیت باقی مانده: <input id="discount_remaining_capacity_0" name="discount_remaining_capacity_0" type="number"  value="1" step="1">
                درصد تخفیف: <input id="discount_percent_0" name="discount_percent_0"  type="number" value="0.0" step="0.1">
                توضیحات:  <textarea id="discount_description_0" name="discount_description_0"></textarea>
            </div>

        </div>
        <div id="remove_discount"></div>
        <button  type="button" id="add_discount" onclick="addDiscount()">افزودن</button>
        <br> <br>






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
                <label for="picture">تصاویر (انتخاب چند تصویر همزمان)</label><span style=" color: red;">★حداقل سه تصویر وارد شود</span>
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

            <div id="features"  style="overflow-x:auto;">
                <label for="features">خدمات تور و وسایل مورد نیاز و اختیاری</label>
                <table id="features_table">
                    <tr>
                        <td style="width: 50px; height: 50px;display: block;"></td>
                        <td>فعال</td>
                        <td>اختیاری</td>
                        <td>همراه بیاورد</td>
                        <td>عنوان</td>
                        <td>توضیحات</td>
                        <td>هزینه</td>
                        <td>تعداد</td>
                        <td>ظرفیت</td>
                        <td>توضیحات تکمیلی</td>
                    </tr>
                    @foreach($features as $feature)
                        <tr>
                            <td style="width: 50px; height: 50px;display: block;"><img src="{{URL::to('features/getFile/'.$feature->feature_id.'/'.$feature->feature_guid.'/4.11')}}"  style="display: block; width: 100%; height: 100%;"/></td>
                            <td><input type="checkbox" id="feature_{{$feature->feature_id}}_checked" name="feature_{{$feature->feature_id}}_checked"></td>
                            <td><input type="checkbox" id="feature_{{$feature->feature_id}}_optional" name="feature_{{$feature->feature_id}}_optional"></td>
                            <td><input type="checkbox" id="feature_{{$feature->feature_id}}_required" name="feature_{{$feature->feature_id}}_required"></td>
                            <td>{{$feature->name}}</td>
                            <td>{{$feature->description}}</td>
                            <td><input type="number" step="1000" id="feature_{{$feature->feature_id}}_cost" name="feature_{{$feature->feature_id}}_cost"></td>
                            <td><input type="number" step="1" id="feature_{{$feature->feature_id}}_count" name="feature_{{$feature->feature_id}}_count"></td>
                            <td><input type="number" step="1" id="feature_{{$feature->feature_id}}_capacity" name="feature_{{$feature->feature_id}}_capacity"></td>
                            <td><textarea id="feature_{{$feature->feature_id}}_description" name="feature_{{$feature->feature_id}}_description"></textarea></td>
                            <input type="hidden" id="feature_{{$feature_counter}}" name="feature_{{$feature_counter}}" value="{{$feature->feature_id}}">
                            <?php
                            $feature_counter += 1;

                            ?>
                        </tr>

                    @endforeach

                </table>
            </div>
            <br><br>

        <div class="row direction" style="margin-top: 40px">
            <div class="col-md-12">
                <fieldset>

                    <input type="checkbox" id="has_wholesale_discount" name="has_wholesale_discount">فعالسازی تخفیف گروهی</input>
                    <br> <br>
                    <label class="float"> درصد تخفیف برای فرد به ازای هر همراه:</label>
                    <input id="wholesale_discount_percent" name="wholesale_discount_percent" type="number" value="0.0" step="0.1">
                    <br> <br>

                </fieldset>
            </div>
        </div>
        <br><br>

            <div class="row direction" style="margin-top: 40px">
                <div class="col-md-12">
                    <fieldset>
                        آدرس مبدا و مکان های سوار شدن:<span style=" color: red;">★</span><br>
                        {{--<textarea id="start_address" name="start_address"></textarea>--}}



                        <div id="start_addresses">
                            <div id="div_start_address_0">

                                آدرس: <textarea id="start_address_0" name="start_address_0"></textarea>
                            </div>
                            <div id="div_gathering_start_date_time_0">
                                تاریخ و ساعت حرکت: ex: 2019-03-06 21:00:00<span style=" color: red;">★</span><br>
                                <input type="text" id="gathering_start_date_time_0" name="gathering_start_date_time_0">
                                <br> <br>
                            </div>
                            <div id="div_gathering_end_date_time_0">
                                تاریخ و ساعت بازگشت: ex: 2019-03-06 21:00:00<span style=" color: red;">★</span><br>
                                <input type="text" id="gathering_end_date_time_0" name="gathering_end_date_time_0">
                                <br> <br>
                            </div>


                        </div>
                        <div id="remove_start_address"></div>
                        <button  type="button" id="add_start_address" onclick="addStartAddress()">افزودن</button>



                        <br> <br>
                        توضیحات مبدا و مکان های سوار شدن:<br>
                        {{--<textarea id="start_description" name="start_description"></textarea>--}}
                        <input name="start_description" type="hidden">
                        <div id="start_description_editor_container" >{!!old('start_description')!!}</div>
                        <br> <br>
                        <legend>انتخاب مبدا و مکان های سوار شدن روی نقشه</legend>
                        <div id="startMap" style="width:100%;height:500px;">
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row direction" style="margin-top: 40px">
                <div class="col-md-12">
                    <fieldset>
                        آدرس مقصد یا مقاصد تور:<span style=" color: red;">★</span><br>
                        {{--<textarea id="end_address" name="end_address"></textarea>--}}



                        <div id="end_addresses">
                            <div id="div_end_address_0">

                                آدرس: <textarea id="end_address_0" name="end_address_0"></textarea>
                            </div>
                            <div id="div_destination_start_date_time_0">
                                تاریخ و ساعت حرکت: ex: 2019-03-06 21:00:00<span style=" color: red;">★</span><br>
                                <input type="text" id="destination_start_date_time_0" name="destination_start_date_time_0">
                                <br> <br>
                            </div>
                            <div id="div_destination_end_date_time_0">
                                تاریخ و ساعت بازگشت: ex: 2019-03-06 21:00:00<span style=" color: red;">★</span><br>
                                <input type="text" id="destination_end_date_time_0" name="destination_end_date_time_0">
                                <br> <br>
                            </div>


                        </div>
                        <div id="remove_end_address"></div>
                        <button  type="button" id="add_end_address" onclick="addEndAddress()">افزودن</button>







                        <br> <br>
                        توضیحات مقصد یا مقاصد تور:<br>
                        {{--<textarea id="end_description" name="end_description"></textarea>--}}
                        <input name="end_description" type="hidden">
                        <div id="end_description_editor_container" >{!!old('end_description')!!}</div>
                        <br> <br>
                        <legend>انتخاب مقصد یا مقاصد روی نقشه</legend>
                        <div id="endMap" style="width:100%;height:500px;">
                        </div>
                    </fieldset>
                </div>
            </div>

            <br>
            <input type="submit" value="ثبت">


        <div><a href="{{ url()->previous() }}">بازگشت</a></div>




    </div>
    </form>
@endsection
@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function () {
        initializeStartMap();
        initializeEndMap();
    });


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

    var tour_description_quill = new Quill('#tour_description_editor_container', {
        modules: {
            syntax: true,
            toolbar: toolbarOptions
        },
        placeholder: '...',
        theme: 'snow'
    });

    var start_description_quill = new Quill('#start_description_editor_container', {
        modules: {
            syntax: true,
            toolbar: toolbarOptions
        },
        placeholder: '...',
        theme: 'snow'
    });

    var end_description_quill = new Quill('#end_description_editor_container', {
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
        var description = document.querySelector('input[name=description]');
        description.value = tour_description_quill.root.innerHTML;

        var start_description = document.querySelector('input[name=start_description]');
        start_description.value = start_description_quill.root.innerHTML;

        var end_description = document.querySelector('input[name=end_description]');
        end_description.value = end_description_quill.root.innerHTML;

        return true;
    };


    function addLeader() {

        var count = $("#leaders > div").length;

        var selectId = "leader_" + count;
        var leaderSelect = "<select name="+selectId+" id="+selectId+">\n" +
            "                        @foreach($leaders as $leader)\n" +
            "                            <option value='{{$leader->user_id}}'>{{$leader->name_family}}</option>\n" +
            "                        @endforeach\n" +
            "\n" +
            "                    </select>";
        var removeButtonId = "removeLeaderButton";
        var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeLeader(" + count + ");\" class=\"btn btn-primary\">\n" +
            "حذف" +
            "</button>";

        var divLeaderId = "div_leader_" + count;
        var divLeader = "<div id="+divLeaderId+">"+leaderSelect+"</div>";

        $("#leaders").append(divLeader);

        if(document.getElementById(removeButtonId) != null)
            document.getElementById(removeButtonId).remove();
        $("#remove_leader").append(removeButton);
    }


    function removeLeader(count_id) {

        if(count_id > 0)
        {
            swal({
                title: 'آیا اطمینان دارید؟',
                text: 'این عملیات قابل بازگشت نمی باشد',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'بیخیال'

            }).then((result) => {
                if(result.value
                )
                {
                    document.getElementById("div_leader_" + count_id).remove();

                    var count = $("#leaders > div").length;

                    var removeButtonId = "removeLeaderButton";
                    var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeLeader(" + (count-1 )+ ");\" class=\"btn btn-primary\">\n" +
                        "حذف" +
                        "</button>";
                    if(document.getElementById(removeButtonId) != null)
                        document.getElementById(removeButtonId).remove();

                    if(count > 1)
                        $("#remove_leader").append(removeButton);
                    if(count > 1)
                        count -= 1;

                }
            })
        }

    }

    function addSocial() {

        var count = $("#socials > div").length;

        // var inputNameId = "social_name_" + count;
        // var socialNameInput = "نام:"+"<input type=\"text\" name="+inputNameId+" id="+inputNameId+">";

        var selectNameId = "social_name_" + count;

        var socialNameSelect ="<select id="+selectNameId+" name="+selectNameId+">\n" +
            "@foreach(\App\Tour::Socials as $social)\n" +
            "<option value='{{$social}}'>{{\App\Tour::getSocialStringByCode($social)}}</option>\n" +
            "@endforeach\n" +
            "</select>";

        var inputAddressId = "social_address_" + count;
        var socialAddressInput = "آدرس:"+"<input type=\"text\" name="+inputAddressId+" id="+inputAddressId+">";

        var removeButtonId = "removeSocialButton";
        var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeSocial(" + count + ");\" class=\"btn btn-primary\">\n" +
            "حذف" +
            "</button>";

        var divSocialId = "div_social_" + count;
        var divSocial = "<div id="+divSocialId+">"+socialNameSelect+socialAddressInput+"</div>";

        $("#socials").append(divSocial);

        if(document.getElementById(removeButtonId) != null)
            document.getElementById(removeButtonId).remove();
        $("#remove_social").append(removeButton);
    }


    function removeSocial(count_id) {

        if(count_id > 0)
        {
            swal({
                title: 'آیا اطمینان دارید؟',
                text: 'این عملیات قابل بازگشت نمی باشد',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'بیخیال'

            }).then((result) => {
                if(result.value
                )
                {
                    document.getElementById("div_social_" + count_id).remove();

                    var count = $("#socials > div").length;

                    var removeButtonId = "removeSocialButton";
                    var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeSocial(" + (count-1) + ");\" class=\"btn btn-primary\">\n" +
                        "حذف" +
                        "</button>";
                    if(document.getElementById(removeButtonId) != null)
                        document.getElementById(removeButtonId).remove();

                    if(count > 1)
                        $("#remove_social").append(removeButton);
                    if(count > 1)
                        count -= 1;

                }
            })
        }
    }

    function addDiscount() {

        var count = $("#discounts > div").length;

        var inputCodeId = "discount_code_" + count;
        var discountCodeInput = "کد:"+"<input type=\"text\" name="+inputCodeId+" id="+inputCodeId+">";

        var inputCapacityId = "discount_capacity_" + count;
        var discountCapacityInput = "ظرفیت:"+"<input type=\"number\" name="+inputCapacityId+" id="+inputCapacityId+" value=\"1\" step=\"1\">";

        var inputRemainingCapacityId = "discount_remaining_capacity_" + count;
        var discountRemainingCapacityInput = "ظرفیت باقی مانده:"+"<input type=\"number\" name="+inputRemainingCapacityId+" id="+inputRemainingCapacityId+" value=\"1\" step=\"1\">";

        var inputPercentId = "discount_percent_" + count;
        var discountPercentInput = "درصد تخفیف:"+"<input type=\"number\" name="+inputPercentId+" id="+inputPercentId+" value=\"0.0\" step=\"0.1\">";

        var inputDescriptionId = "discount_description_" + count;
        var discountDescriptionInput = "توضیحات:"+"<textarea name="+inputDescriptionId+" id="+inputDescriptionId+"></textarea>";

        var removeButtonId = "removeDiscountButton";
        var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeDiscount(" + count + ");\" class=\"btn btn-primary\">\n" +
            "حذف" +
            "</button>";

        var divDiscountId = "div_discount_" + count;
        var divDiscount = "<div id="+divDiscountId+">"+discountCodeInput+discountCapacityInput+discountRemainingCapacityInput+discountPercentInput+discountDescriptionInput+"</div>";

        $("#discounts").append(divDiscount);

        if(document.getElementById(removeButtonId) != null)
            document.getElementById(removeButtonId).remove();
        $("#remove_discount").append(removeButton);
    }


    function removeDiscount(count_id) {

        if(count_id > 0)
        {
            swal({
                title: 'آیا اطمینان دارید؟',
                text: 'این عملیات قابل بازگشت نمی باشد',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'بیخیال'

            }).then((result) => {
                if(result.value
                )
                {
                    document.getElementById("div_discount_" + count_id).remove();

                    var count = $("#discounts > div").length;

                    var removeButtonId = "removeDiscountButton";
                    var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeDiscount(" + (count-1) + ");\" class=\"btn btn-primary\">\n" +
                        "حذف" +
                        "</button>";
                    if(document.getElementById(removeButtonId) != null)
                        document.getElementById(removeButtonId).remove();

                    if(count > 1)
                        $("#remove_discount").append(removeButton);
                    if(count > 1)
                        count -= 1;

                }
            })
        }
    }

    function setStartPoint() {

        // alert(startLat);
        // alert(startLon);

        document.getElementById("start_point_lat").remove();
        document.getElementById("start_point_lon").remove();

        var inputStartPointLat = "<input type=\"hidden\" id=\"start_point_lat\" name=\"start_point_lat\" value='"+startLat+"'>";
        var inputStartPointLon = "<input type=\"hidden\" id=\"start_point_lon\" name=\"start_point_lon\" value='"+startLon+"'>";

        $("#start_point").append(inputStartPointLat+inputStartPointLon);
    }

    function setEndPoint() {

        // alert(endLat);
        // alert(endLon);

        document.getElementById("end_point_lat").remove();
        document.getElementById("end_point_lon").remove();

        var inputEndPointLat = "<input type=\"hidden\" id=\"end_point_lat\" name=\"end_point_lat\" value='"+endLat+"'>";
        var inputEndPointLon = "<input type=\"hidden\" id=\"end_point_lon\" name=\"end_point_lon\" value='"+endLon+"'>";

        $("#end_point").append(inputEndPointLat+inputEndPointLon);
    }


    function addStartAddress() {

        var count = $("#start_addresses > div").length;
        count = count /3;

        var textAreaAddressId = "start_address_" + count;

        var startAddressTextArea = "آدرس:"+"<textarea type=\"text\" name="+textAreaAddressId+" id="+textAreaAddressId+"></textarea>";

        var startDateTimeId = "gathering_start_date_time_" + count;
        var startdateTimeInput = "                                تاریخ و ساعت حرکت: ex: 2019-03-06 21:00:00<span style=\" color: red;\">★</span><br>\n" +
            "                                <input type=\"text\" id="+startDateTimeId+" name="+startDateTimeId+">\n" +
            "                                <br> <br>\n";

        var endDateTimeId = "gathering_end_date_time_" + count;
        var endDateTimeInput = "                                تاریخ و ساعت بازگشت: ex: 2019-03-06 21:00:00<span style=\" color: red;\">★</span><br>\n" +
            "                                <input type=\"text\" id="+endDateTimeId+" name="+endDateTimeId+">\n" +
            "                                <br> <br>\n";

        var removeButtonId = "removeStartAddressButton";
        var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeStartAddress(" + count + ");\" class=\"btn btn-primary\">\n" +
            "حذف" +
            "</button>";

        var divStartAddressId = "div_start_address_" + count;
        var divStartAddress = "<div id="+divStartAddressId+">"+startAddressTextArea+"</div>";

        var divStartDateTimeId = "div_gathering_start_date_time_" + count;
        var divStartDateTime = "<div id="+divStartDateTimeId+">"+startdateTimeInput+"</div>";

        var divEndDateTimeId = "div_gathering_end_date_time_" + count;
        var divEndDateTime = "<div id="+divEndDateTimeId+">"+endDateTimeInput+"</div>";

        $("#start_addresses").append(divStartAddress);
        $("#start_addresses").append(divStartDateTime);
        $("#start_addresses").append(divEndDateTime);

        if(document.getElementById(removeButtonId) != null)
            document.getElementById(removeButtonId).remove();
        $("#remove_start_address").append(removeButton);
    }


    function removeStartAddress(count_id) {

        if(count_id > 0)
        {
            swal({
                title: 'آیا اطمینان دارید؟',
                text: 'این عملیات قابل بازگشت نمی باشد',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'بیخیال'

            }).then((result) => {
                if(result.value
                )
                {
                    document.getElementById("div_start_address_" + count_id).remove();
                    document.getElementById("div_gathering_start_date_time_" + count_id).remove();
                    document.getElementById("div_gathering_end_date_time_" + count_id).remove();

                    var count = $("#start_addresses > div").length;
                    count = count /3;

                    var removeButtonId = "removeStartAddressButton";
                    var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeStartAddress(" + (count-1) + ");\" class=\"btn btn-primary\">\n" +
                        "حذف" +
                        "</button>";
                    if(document.getElementById(removeButtonId) != null)
                        document.getElementById(removeButtonId).remove();

                    if(count > 1)
                        $("#remove_start_address").append(removeButton);
                    if(count > 1)
                        count -= 1;

                }
            })
        }
    }


    function addEndAddress() {

        var count = $("#end_addresses > div").length;
        count = count /3;

        var textAreaAddressId = "end_address_" + count;

        var endAddressTextArea = "آدرس:"+"<textarea type=\"text\" name="+textAreaAddressId+" id="+textAreaAddressId+"></textarea>";

        var endDateTimeId = "destination_end_date_time_" + count;
        var endDateTimeInput =  "                                تاریخ و ساعت بازگشت: ex: 2019-03-06 21:00:00<span style=\" color: red;\">★</span><br>\n" +
            "                                <input type=\"text\" id="+endDateTimeId+" name="+endDateTimeId+">\n" +
            "                                <br> <br>\n";

        var startDateTimeId = "destination_start_date_time_" + count;
        var startDateTimeInput =  "                                تاریخ و ساعت حرکت: ex: 2019-03-06 21:00:00<span style=\" color: red;\">★</span><br>\n" +
            "                                <input type=\"text\" id="+startDateTimeId+" name="+startDateTimeId+">\n" +
            "                                <br> <br>\n";

        var removeButtonId = "removeEndAddressButton";
        var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeEndAddress(" + count + ");\" class=\"btn btn-primary\">\n" +
            "حذف" +
            "</button>";

        var divEndAddressId = "div_end_address_" + count;
        var divEndAddress = "<div id="+divEndAddressId+">"+endAddressTextArea+"</div>";

        var divEndDateTimeId = "div_destination_end_date_time_" + count;
        var divEndDateTime = "<div id="+divEndDateTimeId+">"+endDateTimeInput+"</div>";

        var divStartDateTimeId = "div_destination_start_date_time_" + count;
        var divStartDateTime = "<div id="+divStartDateTimeId+">"+startDateTimeInput+"</div>";

        $("#end_addresses").append(divEndAddress);
        $("#end_addresses").append(divEndDateTime);
        $("#end_addresses").append(divStartDateTime);

        if(document.getElementById(removeButtonId) != null)
            document.getElementById(removeButtonId).remove();
        $("#remove_end_address").append(removeButton);
    }


    function removeEndAddress(count_id) {

        if(count_id > 0)
        {
            swal({
                title: 'آیا اطمینان دارید؟',
                text: 'این عملیات قابل بازگشت نمی باشد',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'بیخیال'

            }).then((result) => {
                if(result.value
                )
                {
                    document.getElementById("div_end_address_" + count_id).remove();
                    document.getElementById("div_destination_start_date_time_" + count_id).remove();
                    document.getElementById("div_destination_end_date_time_" + count_id).remove();

                    var count = $("#end_addresses > div").length;
                    count = count /3;

                    var removeButtonId = "removeEndAddressButton";
                    var removeButton = "<button  type=\"button\" id=" + removeButtonId + "  onclick=\"removeEndAddress(" + (count-1) + ");\" class=\"btn btn-primary\">\n" +
                        "حذف" +
                        "</button>";
                    if(document.getElementById(removeButtonId) != null)
                        document.getElementById(removeButtonId).remove();

                    if(count > 1)
                        $("#remove_end_address").append(removeButton);
                    if(count > 1)
                        count -= 1;

                }
            })
        }
    }

    //for map
    var startPopup;
    var endPopup;

    var startMarker;
    var endMarker;

    var startLat;
    var startLon ;

    var startMap;
    var endMap;
    var endLat;
    var endLon ;

    var mapStartLayer;
    var mapEndLayer;


    function initializeStartMap() {

        // alert('in refreshMap');
        setTimeout(function () {
            if (typeof startMap !== 'undefined') {
                // the variable is defined
                startMap.remove();
            }

            startLat = 29.617248;
            startLon = 52.543423;

            // alert(startLat);
            // alert(startLon);

            // alert('in !lat');
            startMap = L.map('startMap').setView([startLat, startLon], 16);


            mapStartLayer = L.tileLayer('https://developers.parsijoo.ir/web-service/v1/map/?type=tile&x={x}&y={y}&z={z}&apikey=4a910bd09861449b834625701bad4f2d',
                {
                    maxZoom: 19,
                }).addTo(startMap);


            startPopup = L.popup()
                    .setLatLng([startLat, startLon])
                .setContent("مبدا"
                    + "<br>" + 'عرض جفرافیایی' + ":" + startLat
                    + "<br>" + 'طول جفرافیایی' + ":" + startLon)
                .addTo(startMap);

            startMarker = L.marker([startLat, startLon], {
                icon: L.icon({
                    // iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
                    iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                    className: 'blinking'
                })
            }).addTo(startMap);


            startMap.on('click', onStartMapClick);

            setStartPoint();

        }, 2000);


    }


    // on start map click
    function onStartMapClick(e) {

        // alert('onMapClick');


        // alert('ddd');
        // marker = new L.marker(e.latlng).addTo(map);
        startLat = (e.latlng.lat);
        startLon = (e.latlng.lng);

        // alert(startLat);
        // alert(startLon);

        if (typeof startPopup !== 'undefined')
        {
            startMap.removeLayer(startPopup);
            startMap.removeLayer(startMarker);

            startPopup.setLatLng(e.latlng)
            // crea un form all'interno del popup
                .setContent("مبدا"
                    + "<br>" + 'عرض جفرافیایی' + ":" + startLat
                    + "<br>" + 'طول جفرافیایی' + ":" + startLon)
                .openOn(startMap);

            startMarker = L.marker([startLat, startLon], {
                icon: L.icon({
                    // iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
                    iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                    className: 'blinking'
                })
            }).addTo(startMap);
        }
        else
        {
            startPopup = L.popup()
                .setLatLng([startLat, startLon])
                .setContent("مبدا"
                    + "<br>" + 'عرض جفرافیایی' + ":" + startLat
                    + "<br>" + 'طول جفرافیایی' + ":" + startLon)
                .addTo(startMap);

            startMarker = L.marker([startLat, startLon], {
                icon: L.icon({
                    // iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
                    iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                    className: 'blinking'
                })
            }).addTo(startMap);
        }

        setStartPoint();

    }

    function initializeEndMap() {

        // alert('in refreshMap');
        setTimeout(function () {
            if (typeof endMap !== 'undefined') {
                // the variable is defined
                endMap.remove();
            }

            endLat = 29.617248;
            endLon = 52.543423;

            // alert('in !lat');
            endMap = L.map('endMap').setView([endLat, endLon], 16);


            endLayer = L.tileLayer('https://developers.parsijoo.ir/web-service/v1/map/?type=tile&x={x}&y={y}&z={z}&apikey=4a910bd09861449b834625701bad4f2d',
                {
                    maxZoom: 19,
                }).addTo(endMap);


            endPopup = L.popup()
                .setLatLng([endLat, endLon])
                .setContent("مقصد"
                    + "<br>"
                    + 'عرض جفرافیایی' + ":" + endLat
                    + "<br>" + 'طول جفرافیایی' + ":" + endLon)
                .addTo(endMap);

            endMarker = L.marker([endLat, endLon], {
                icon: L.icon({
                    // iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
                    iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                    className: 'blinking'
                })
            }).addTo(endMap);


            endMap.on('click', onEndMapClick);

            setEndPoint();

        }, 2000);


    }

    // on end map click
    function onEndMapClick(e) {

        // alert('onMapClick');


        // alert('ddd');
        // marker = new L.marker(e.latlng).addTo(map);
        endLat = (e.latlng.lat);
        endLon = (e.latlng.lng);

        // alert(lat);
        // alert(lon);

        if (typeof endPopup !== 'undefined')
        {
            endMap.removeLayer(endPopup);
            endMap.removeLayer(endMarker);

            endPopup.setLatLng(e.latlng)
            // crea un form all'interno del popup
                .setContent("مقصد"
                    + "<br>"
                    + 'عرض جفرافیایی' + ":" + endLat
                    + "<br>" + 'طول جفرافیایی' + ":" + endLon)
                .addTo(endMap);

            endMarker = L.marker([endLat, endLon], {
                icon: L.icon({
                    // iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
                    iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                    className: 'blinking'
                })
            }).addTo(endMap);
        }
        else
        {
            endPopup = L.popup()
                .setLatLng([endLat, endLon])
                .setContent("مقصد"
                    + "<br>"
                    + 'عرض جفرافیایی' + ":" + endLat
                    + "<br>" + 'طول جفرافیایی' + ":" + endLon)
                .addTo(endMap);

            endMarker = L.marker([endLat, endLon], {
                icon: L.icon({
                    // iconUrl: 'https://unpkg.com/leaflet@1.0.3/dist/images/marker-icon.png',
                    iconUrl: '{{url('/assets-leaflet/img/marker-icon.png')}}',
                    className: 'blinking'
                })
            }).addTo(endMap);
        }
        setEndPoint();
    }

</script>
@stop