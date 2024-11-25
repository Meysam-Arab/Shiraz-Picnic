@extends('layouts.master')
@section('title',  trans('messages.tlt_ter'))
@section('content')
    <style>
        h4 {
            line-height: 1.7em;
        }

        p {
            text-align: justify;
        }

        .card-body {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem;
        }

        .card {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: .25rem;
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
            margin-top: 10px;
            padding: 10px;
        }
        .col-md-6{
            padding: 10px;
        }
    </style>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-3"></div>
        <div class="col-md-6 text-center text-center-mobile wow animated fadeInUp">
            <h3 class="heading no-margin wow animated fadeInUp">سوالات متداول</h3>
            <br/>
            <div class="row" style="direction: rtl">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="panel-one">
                            {{--<div class="user-img"><img alt="" src="images/testimonail_1.jpg"></div>--}}
                            <div class="testi-info">
                                <h4>{{trans('messages.tlt_faq_1')}}</h4>
                                {{--<h5>Lorem ipsum dolor sit amet</h5>--}}
                                <p>{{trans('messages.des_faq_1')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="panel-one">
                            {{--<div class="user-img"><img alt="" src="images/testimonail_2.jpg"></div>--}}
                            <div class="testi-info">
                                <h4>{{trans('messages.tlt_faq_2')}}</h4>
                                {{--<h5>Lorem ipsum dolor sit amet</h5>--}}
                                <p>{{trans('messages.des_faq_2')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="direction: rtl">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="panel-one">
                            {{--<div class="user-img"><img alt="" src="images/testimonail_3.jpg"></div>--}}
                            <div class="testi-info">
                                <h4>{{trans('messages.tlt_faq_3')}}</h4>
                                {{--<h5>Lorem ipsum dolor sit amet</h5>--}}
                                <p>{{trans('messages.des_faq_3')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row" style="direction: rtl">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="panel-one">
                            {{--<div class="user-img"><img alt="" src="images/testimonail_4.jpg"></div>--}}
                            <div class="testi-info">
                                <h4>{{trans('messages.tlt_faq_4')}}</h4>
                                {{--<h5>Lorem ipsum dolor sit amet</h5>--}}
                                <p>{{trans('messages.des_faq_4')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="panel-one">
                            {{--<div class="user-img"><img alt="" src="images/testimonail_4.jpg"></div>--}}
                            <div class="testi-info">
                                <h4>{{trans('messages.tlt_faq_5')}}</h4>
                                {{--<h5>Lorem ipsum dolor sit amet</h5>--}}
                                <p>{{trans('messages.des_faq_5')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="direction: rtl">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="panel-one">
                            {{--<div class="user-img"><img alt="" src="images/testimonail_4.jpg"></div>--}}
                            <div class="testi-info">
                                <h4>{{trans('messages.tlt_faq_6')}}</h4>
                                {{--<h5>Lorem ipsum dolor sit amet</h5>--}}
                                <p>{{trans('messages.des_faq_6')}}</p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-sm-6">
                    <div class="card">
                        <div class="panel-one">
                            {{--<div class="user-img"><img alt="" src="images/testimonail_4.jpg"></div>--}}
                            <div class="testi-info">
                                <h4>{{trans('messages.tlt_faq_7')}}</h4>
                                {{--<h5>Lorem ipsum dolor sit amet</h5>--}}
                                <p>{{trans('messages.des_faq_7')}}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        </section>
        </p>
    </div>
@endsection