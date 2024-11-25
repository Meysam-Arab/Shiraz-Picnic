<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{{URL::to('css/mainPage/bootstrap.css')}}">
<link rel="stylesheet" href="{{URL::to('css/dashboard/templatemo-style.css')}}">
<script src="{{URL::to('assets-switalert/sweetalert2.all.min.js')}}"></script>
<div class="container" style="margin-top: 40px">
    <div class="row">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>
</div>
<script src="{{URL::to('js/mainPage/jquery.min.js')}}"></script>
<script src="{{URL::to('js/mainPage/bootstrap.min.js')}}"></script>

@yield('page-js-files')
@yield('page-js-script')