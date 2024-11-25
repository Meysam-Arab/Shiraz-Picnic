<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="{{URL::to('css/mainPage/icomoon.css')}}">
<link rel="stylesheet" href="{{URL::to('css/mainPage/bootstrap.css')}}">
<link rel="stylesheet" href="{{URL::to('css/mainPage/style.css')}}">
<script src="{{URL::to('assets-switalert/sweetalert2.all.min.js')}}"></script>
<div class="container" style="margin-top: 40px">
    <div class="row">
        <div class="js-sticky">
            <div class="fh5co-main-nav">
                <div class="container-fluid">
                    <div class="fh5co-menu-1">
                        <a href="{{url('/')}}" data-nav-section="home">خانه</a>
                        {{--<a href="#" data-nav-section="about">About</a>--}}
                        <a href="{{url('gardens/listAll')}}" data-nav-section="features">رزرو باغ</a>
                    </div>
                    <div class="fh5co-logo">
                        <a href="{{url('/')}}" style="padding: 15px 0px !important;"><img src="{{URL::to('images/logo_small_a.png')}}" class="img-responsive" alt="شیراز-پیک نیک"></a>
                    </div>
                    <div class="fh5co-menu-2">
                        {{--<a href="#" data-nav-section="menu">Menu</a>--}}
                        <a href="{{url('tours/listAll')}}" data-nav-section="events">تورهای تفریحی</a>
                        <a href="{{url('/#fh5co-contact')}}" data-nav-section="reservation">تماس با ما</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
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