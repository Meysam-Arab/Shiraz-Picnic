{{-- Meysam Arab - 13950829--}}
<!DOCTYPE html>
<html>

<head>
    <title>404</title>
    <link href = "https://fonts.googleapis.com/css?family=Lato:100" rel = "stylesheet"
          type = "text/css">

    <style>
        html, body {
            height: 100%;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'BRoya',Tahoma;
        }
        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }
        .content {
            text-align: center;
            display: inline-block;
        }
        .title {
            margin-top: 30px;
            font-size: 34px;
            margin-bottom: 40px;
        }
    </style>

</head>
<body>

<div class = "container">
    <div class = "content">
        <div style="width: 100%"><img src="{{url('/images/logo_big_a.png')}}"></div>
        <div class = "title">{{trans('messages.msgError404')}}</div>

    </div>
</div>

</body>
</html>