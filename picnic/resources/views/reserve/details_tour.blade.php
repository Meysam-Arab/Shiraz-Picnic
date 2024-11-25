@extends('layouts.master_empty')
@section('title',  trans('messages.tltTicketDetails'))

@section('content')
    <div style="direction: rtl">
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
        @if(isset($reserve))
            <div class="col-md-4">
                <?php
                if (isset($png)) {
                    echo "<img src='data:image/png;base64," . $png . "' style='' id='qrImg' alt='شیراز پیک نیک - ".$tour->title."'>";
                } else {
                }
                ?>
                <div class="alert alert-danger">
                    همراه داشتن کارت ملی (فقط فرد رزرو کننده) الزامی است.
                </div>

                <fieldset id="filedsetII">
                    <legend>
                        رسید خرید
                    </legend>
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-5 label01" style="font-size: 14px">
                            {{trans('messages.lblTicketNumber')}} :
                        </div>
                        <div class="col-md-7 label02" style="font-size: 14px">
                            {{$reserve->reserve_id}}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-5 label01" style="font-size: 14px">
                            {{trans('messages.lblTitle')}} :
                        </div>
                        <div class="col-md-7 label02" style="font-size: 14px">
                            {{$tour->title}}
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-5 label01" style="font-size: 14px">
                            {{trans('messages.lblAddress')}} :
                        </div>
                        <div class="col-md-7 label02" style="font-size: 14px">

                        @if(!is_array($tour->tour_address->address))
                            <!-- for old version tours-->
                                {{$tour->tour_address->address}}
                        @else
                                <?php
                                $i=0;
                                $count = count($tour->tour_address->address);
                                ?>
                            @foreach($tour->tour_address->address as $tour_address)
                                        @if($count > 1 && $i != $count-1)
                                            {{$tour_address->address."-"}}
                                        @else
                                            {{$tour_address->address}}
                                        @endif

                                        <?php $i++ ?>

                            @endforeach
                        @endif


                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-5 label01" style="font-size: 14px">
                            {{trans('messages.lblCoordinationTel')}} :
                        </div>
                        <div class="col-md-7 label02" style="font-size: 14px">
                            {{$tour->info->coordination_tel}}
                        </div>
                    </div>


                </fieldset>

            </div>

            <div class="col-md-8" style="margin-bottom: 200px">


                @if(isset($reserve->info->persons) && (count($reserve->info->persons)>0))
                    <?php  $personCount = 0; ?>
                    <fieldset id="filedsetIII" style="margin-top: 10px">
                        <legend>
                            افراد ثبت نامی
                        </legend>

                        <div class="col-md-12" style="margin-top: 10px;">

                            <div class="col-md-12">

                                <div style="overflow-x:auto;">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th style="text-align: center;color: #0b93d5;">نام و نام خانوادگی</th>
                                            <th style="text-align: center;color: #0b93d5;">کد ملی</th>
                                            <th style="text-align: center;color: #0b93d5;">تلفن همراه</th>
                                            <th style="text-align: center;color: #0b93d5;">محل تجمع</th>
                                            <th style="text-align: center;color: #0b93d5;">موارد خاص</th>

                                        </tr>
                                        </thead>
                                        <tbody>


                                        @foreach($reserve->info->persons as $person)


                                            <tr>
                                                @if($personCount == 0)

                                                    <td style="text-align: center">{{$person->name_and_family}} (خریدار)</td>
                                                @else
                                                    <td style="text-align: center">{{$person->name_and_family}}</td>
                                                @endif


                                                <td style="text-align: center">{{$person->national_code}}</td>

                                                @if(isset($person->mobile))
                                                    <td style="text-align: center">{{$person->mobile}}</td>
                                                @else
                                                    <td style="text-align: center"></td>
                                                @endif

                                                @if(isset($person->start_address))
                                                    <td style="text-align: center">{{$person->start_address}}</td>
                                                @else
                                                    <td style="text-align: center"></td>
                                                @endif

                                                @if(isset($person->personal_description))
                                                    <td style="text-align: center">{{$person->personal_description}}</td>
                                                @else
                                                    <td style="text-align: center"></td>
                                                @endif

                                            </tr>

                                            <?php $personCount++; ?>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </fieldset>
                @endif


                @if(isset($reserve->info->informations) && (count($reserve->info->informations) > 0))
                    <fieldset id="filedsetIII" style="margin-top: 10px">
                        <legend>
                            موارد خریداری شده
                        </legend>

                        <div class="col-md-12" style="margin-top: 10px;">

                            <div class="col-md-12">
                                @php
                                    $count_TD = 1;

                                @endphp

                                <div style="overflow-x:auto;">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            {{--<div class="col-md-5 label01" style="float: right;font-size: 14px">--}}
                                            <th style="text-align: center;color: #0b93d5;">{{trans('messages.lblTitle')}}</th>
                                            <th style="text-align: center;color: #0b93d5;">{{trans('messages.lblNumber')}}</th>
                                            <th style="text-align: center;color: #0b93d5;">{{trans('messages.lblTitle')}}</th>
                                            <th style="text-align: center;color: #0b93d5;">{{trans('messages.lblNumber')}}</th>

                                        </tr>
                                        </thead>
                                        <tbody>


                                        @foreach($reserve->info->informations as $information)

                                            @if($count_TD == 1)
                                                <tr>
                                                    <td style="text-align: center">{{$information->name}}</td>

                                                    <td style="text-align: center">{{$information->count}}</td>

                                                    @php $count_TD++; @endphp
                                                    @else


                                                        <td style="text-align: center">{{$information->name}}</td>

                                                        <td style="text-align: center">{{$information->count}}</td>
                                                        @php $count_TD = 1; @endphp
                                                </tr>
                                                @endif

                                                @endforeach
                                                @if($count_TD > 1)
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </fieldset>
                @endif


                @if(count($tour->social))
                    <fieldset id="filedsetIII" style="margin-top: 10px">
                        <legend>
                            شبکه های اجتماعی
                        </legend>

                        <div class="col-md-12" style="margin-top: 10px;">

                            <div class="col-md-12">
                                @php
                                    $count_TD = 1;

                                @endphp

                                <div style="overflow-x:auto;">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            {{--<div class="col-md-5 label01" style="float: right;font-size: 14px">--}}
                                            <th style="text-align: center;color: #0b93d5;">نام</th>
                                            <th style="text-align: center;color: #0b93d5;">آدرس</th>


                                        </tr>
                                        </thead>
                                        <tbody>


                                        @foreach($tour->social as $social)


                                            <tr>
                                                <td style="text-align: center">{{\App\Tour::getSocialStringByCode($social->code)}}</td>

                                                <td style="text-align: center"><a target="_blank" href="{{$social->address}}">{{$social->address}}</a> </td>

                                            </tr>

                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>


                    </fieldset>
                @endif

                    @if($discount != null)
                        <fieldset id="filedsetIII" style="margin-top: 10px">
                            <legend>
                                توضیحات
                            </legend>

                            <div class="col-md-12" style="margin-top: 10px;">

                                <div class="col-md-12">
                                    {{$discount->description}}
                                </div>

                            </div>


                        </fieldset>
                    @endif



                    @endif
    </div>

            <div class="row">
                <div class="col-md-5 col-md-7" style="margin-top: 5px; margin-bottom: 15px;">
                    <a href="{{url("/")}}" class="btn btn-primary no-print"> {{trans('messages.btnContinue')}} </a>
                    <a onclick="printFunction()" class="btn btn-primary no-print"> چاپ بلیط </a>
                </div>
            </div>

            <div class="row">

                    <p>در صورت تغییر مبدا سوار شدن با شماره موجود در صفحه تور هماهنگی های لازم را انجام دهید</p>

            </div>

        </div>

    <style>
        #qrImg {
            display: block;
            margin-right: auto;
            margin-left: auto;
            min-width: 60%;
        }

        .label01 {
            font-size: large;
            color: #0b93d5;
            font-weight: bold;
        }

        .label02 {
            font-size: medium;
            color: #0e0e0e;
            font-weight: bold;
        }

        #filedsetII div {
            float: right;
        }

        body {
            font-family: 'robotobold';
        }

        .col-md-12 {
            width: 100%;
        }

        @media print {
            .no-print, .no-print * {
                display: none !important;
            }

            #qrImg {
                min-width: 40%;
                width: 40%;
            }
        }
    </style>
    <script>
        function printFunction() {
            window.print();
        }
    </script>
@endsection