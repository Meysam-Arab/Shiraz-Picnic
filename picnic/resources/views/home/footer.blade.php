<!--================ Start Footer Area =================-->
<footer class="footer-area overlay" id="footer">
    <div class="container">
        <div class="row row-padded">
            <div class="col-md-12 text-center">
                <p class="text-center to-animate fadeIn animated"><a href="#" class="js-gotop">برو بالا</a></p>
                @if(Auth::check())
                    <a href="{{url('/users/dashboard')}}" class="btn btn-primary">صفحه کاربری</a>
                @else
                    <a href="{{url('/users/login')}}" class="btn btn-primary">ورود همکاران</a>
                @endif
                <div class="row" style="margin-top: 15px">
                    <div class="col-md-4"></div>
                    <div class="col-md-2"><p class="text-center to-animate fadeIn animated"><a style="font-size: larger"
                                                                                               target="_blank"
                                                                                               href="{{url('/termsOfUse')}}">قوانین
                                و
                                مقررات
                                سایت</a></p></div>
                    <div class="col-md-2"><p class="text-center to-animate fadeIn animated"><a style="font-size: larger"
                                                                                               target="_blank"
                                                                                               href="{{url('/faq')}}">سوالات
                                متداول</a></p></div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                {{--<script src="https://www.zarinpal.com/webservice/TrustCode" type="text/javascript"></script>--}}
                <a href="javascript:showZPTrust();" title="دروازه پرداخت معتبر"><img
                            src="https://cdn.zarinpal.com/badges/trustLogo/1.svg" border="0" alt="دروازه پرداخت معتبر"></a>
            </div>
        </div>
    </div>
</footer>
<!--================ Start Footer Area =================-->