
{{--/**--}}
 {{--* Created by PhpStorm.--}}
 {{--* User: Meysam--}}
 {{--* Date: 04/11/2017--}}
 {{--* Time: 02:03 PM--}}
 {{--*/--}}

<html>
<head></head>
{{--<body style="background: black; color: white">--}}
<body>
{{--<h1>{{$title}}</h1>--}}
{{--<p>{{$content}}</p>--}}
<div style="font-family:IRANSans,\'B Yekan\',\'2 Yekan\',Yekan,Tahoma,\'Helvetica Neue\',Arial,sans-serif;font-size:16px;text-align:right;padding: 0px 5px 0 0; ">
    <div>
        <div style="background-color: #683014;display: block;width: 650px;margin:0px auto;">
            <div style="height: 65px;width: 650px;font-size: 20px;text-align:right;">
                <div style="color:wheat;background-color: #683014;height: 35px;width: 650px;font-size: 20px;text-align:center;">
                    {{trans('messages.msgEmailRegisterDescription2')}}
                </div>
            </div>


            <div style="background-color: #976e6e;height: 65px;width: 650px;font-size: 20px;text-align:right;">
                <div style="background-color: wheat;height: 35px;width: 640px;font-size: 20px;text-align:right;padding-right: 10px">
                    {{trans('messages.msgEmailRegisterDescription3')}}
                </div>
                <div style="background-color: wheat;height: 30px;width: 640px;font-size: 20px;text-align:right;padding-right: 10px">{{$email}}</div>
            </div>
            <div style="background-color: #795548;height: 65px;width: 650px;font-size: 20px;text-align:right;">
                <div style="background-color: wheat;height: 35px;width: 640px;font-size: 20px;text-align:right;padding-right: 10px">
                    {{trans('messages.msgEmailRegisterDescription4')}}
                </div>
                <div style="background-color: wheat;height: 30px;width: 640px;font-size: 20px;text-align:right;padding-right: 10px">{{$pass}}</div>
            </div>

        </div>
    </div>
    <div style="display: block;height: 230px;width: 650px;margin:0px auto;">
        <div style="display:block;height: 30px;background-color: #976e6e;color:white;padding-top: 10px;">
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">

            </div>

            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin-right: 215px;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">
                {{trans('messages.msgEmailRegisterDescription5')}}
            </div>
        </div>
        <div style="display:block;height: 100px;background-color: wheat;color:white;padding-top: 10px;">
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">
            </div>
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin-right: 215px;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">
                <div>{{trans('messages.msgEmailRegisterDescription6')}}</div>
                <div style="color:#0b93d5">info@shirazpicnic.ir</div>
            </div>
        </div>
        <div style="display:block;height: 30px;background-color: #976e6e;color:white;padding-top: 10px;">
            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">

            </div>

            <div style="float: right;width: 215px;    border-collapse: collapse!important;
                color: white;
                font-weight: 400;
                line-height: 1.3;
                margin-right: 215px;
                padding: 0;
                text-align: center;
                vertical-align: top;
                word-wrap: break-word;">
                {{trans('messages.msgEmailRegisterDescription7')}}
            </div>
        </div>
    </div>
</div>
</body>
</html>

