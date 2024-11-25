<div class="js-sticky" style="height: 98px;">
    <div class="fh5co-main-nav">
        <div class="container-fluid">
            <div class="fh5co-menu-1 aInBar">
                <a href="{{url('/#footer')}}" data-nav-section="reservation">تماس با ما</a>
                <a href="{{url('/#fh5co-featured')}}" data-nav-section="features">باغ های خصوصی</a>
            </div>
            <div class="fh5co-logo">
                <a href="https://www.shirazpicnic.ir" style="padding: 15px 0px !important;"><img src="https://www.shirazpicnic.ir/images/logo_big_a.png" class="img-responsive" alt="شیراز-پیک نیک"></a>
            </div>
            <div class="fh5co-menu-2 aInBar">
                <a href="{{url('/#fh5co-events')}}" data-nav-section="events">تورهای گردشگری</a>
                <a href="{{url('/#jssor_1')}}" data-nav-section="home">خانه</a>
            </div>
        </div>

    </div>
</div>
<style>
    .js-sticky {
        display: block;
        box-shadow: -6px 0px 20px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }
    .fh5co-main-nav {
        position: relative;
        background: #fff;
    }
    .fh5co-main-nav:before {
        top: 2px;
    }
    .fh5co-main-nav:before, .fh5co-main-nav:after {
        content: "";
        position: absolute;
        left: 0;
        height: 2px;
        width: 100%;
        background: #e6e6e6;
    }
    .container-fluid {
        margin-right: auto;
        margin-left: auto;
        padding-left: 15px;
        padding-right: 15px;
    }
    .container-fluid:before, .container-fluid:after {
        content: " ";
        display: table;
    }
    .fh5co-main-nav .fh5co-menu-1 {
        text-align: right;
        width: 40.33%;
    }
    .fh5co-main-nav .fh5co-menu-1, .fh5co-main-nav .fh5co-menu-2, .fh5co-main-nav .fh5co-logo {
        /*font-size: large;*/
        font-weight: 600;
        vertical-align: middle;
        float: left;
        line-height: 0;
    }
    .fh5co-menu-2,.fh5co-menu-1{
        margin-top: 15px;
    }
    .fh5co-main-nav .fh5co-menu-1 a.active, .fh5co-main-nav .fh5co-menu-2 a.active, .fh5co-main-nav .fh5co-logo a.active {
        color: #fb6e14;
    }

    .fh5co-main-nav .fh5co-menu-1 a {
        vertical-align: middle;
    }
    .fh5co-main-nav .fh5co-menu-1 a, .fh5co-main-nav .fh5co-menu-2 a, .fh5co-main-nav .fh5co-logo a {
        padding: 35px 10px;
        color: #130d08;
        display: -moz-inline-stack;
        display: inline-block;
        zoom: 1;
        *display: inline;
    }
    .fh5co-main-nav .fh5co-logo {
        text-align: center;
        width: 19.33%;
        font-size: 40px;
        font-family: "Sahel-Bold", Tahoma, serif;
        font-weight: 700;
        font-style: italic;
    }
    .fh5co-main-nav .fh5co-menu-1, .fh5co-main-nav .fh5co-menu-2, .fh5co-main-nav .fh5co-logo {
        vertical-align: middle;
        float: left;
        line-height: 0;
    }
    .fh5co-main-nav .fh5co-menu-2 {
        text-align: left;
        width: 40.33%;
    }
    .fh5co-main-nav .fh5co-menu-1, .fh5co-main-nav .fh5co-menu-2, .fh5co-main-nav .fh5co-logo {
        vertical-align: middle;
        float: left;
        line-height: 0;
    }
    .img-responsive {
        display: block;
        max-width: 30%;
        margin-right: auto;
        margin-left: auto;
        height: auto;
    }
    @media (max-width: 767px) {
        .aInBar{
            display: none !important;
        }
        .fh5co-logo{
            width: 100% !important;
        }
    }
</style>