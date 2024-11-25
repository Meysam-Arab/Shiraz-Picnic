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
                    echo "<img src='data:image/png;base64," . $png . "' style='' id='qrImg'>";
                } else {
                }
                ?>
                <div class="alert alert-danger">
                    همراه داشتن کارت ملی (فقط فرد رزرو کننده) الزامی است.
                </div>
            </div>
            <div class="col-md-8" style="margin-bottom: 200px">

                <fieldset id="filedsetII">
                    <legend>
                        {{trans('messages.tltTicketDetails')}}
                    </legend>
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-3 label01">
                            {{trans('messages.lblTicketNumber')}} :
                        </div>
                        <div class="col-md-9 label02">
                            {{$reserve->reserve_id}}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-3 label01">
                            {{trans('messages.lblGardenName')}} :
                        </div>
                        <div class="col-md-9 label02">
                            {{$garden->name}}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-3 label01">
                            {{trans('messages.lblAddress')}} :
                        </div>
                        <div class="col-md-9 label02">
                            {{$garden->address}}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-3 label01">
                            {{trans('messages.lblNameAndFamily')}} :
                        </div>
                        <div class="col-md-9 label02">
                            {{$reserve->info->name_and_family}}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-3 label01">
                            {{trans('messages.lblNationalCode')}} :
                        </div>
                        <div class="col-md-9 label02">
                            {{$reserve->info->national_code}}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 10px">
                        <div class="col-md-3 label01">
                            {{trans('messages.lblCoordinationTel')}} :
                        </div>
                        <div class="col-md-9 label02">
                            {{$garden->info->coordination_tel}}
                        </div>
                    </div>

                    @if($reserve->info->reserved_kind == \App\Reserve::TypeSeveralDays)
                        <div class="col-md-12" style="margin-top: 10px">
                            <div class="col-md-3 label01">
                                {{trans('messages.lblStartDate')}} :
                            </div>
                            <div class="col-md-9 label02">
                                {{$reserve->start_date}}
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 10px">
                            <div class="col-md-3 label01">
                                {{trans('messages.lblShift')}} :
                            </div>
                            <div class="col-md-9 label02">
                                {{\App\Garden::getShiftStringByCode($reserve->info->shift_id_1)}}
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 10px">
                            <div class="col-md-3 label01">
                                {{trans('messages.lblEndDate')}} :
                            </div>
                            <div class="col-md-9 label02">
                                {{$reserve->end_date}}
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 10px">
                            <div class="col-md-3 label01">
                                {{trans('messages.lblShift')}} :
                            </div>
                            <div class="col-md-9 label02">
                                {{\App\Garden::getShiftStringByCode($reserve->info->shift_id_2)}}
                            </div>
                        </div>
                    @else
                        <div class="col-md-12" style="margin-top: 10px">
                            <div class="col-md-3 label01">
                                {{trans('messages.lblDate')}} :
                            </div>
                            <div class="col-md-9 label02">
                                {{$reserve->start_date}}
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 10px">
                            <div class="col-md-3 label01">
                                {{trans('messages.lblShift')}} :
                            </div>
                            <div class="col-md-9 label02">
                                {{\App\Garden::getShiftStringByCode($reserve->info->shift_id_1)}}
                            </div>
                        </div>
                    @endif


                    <div class="col-md-12" style="margin-top: 10px">
                        <a href="{{url()->previous()}}" class="btn btn-primary no-print"> {{trans('messages.btnBack')}} </a>
                        <a onclick="printFunction()" class="btn btn-primary no-print"> چاپ بلیط </a>
                    </div>
                </fieldset>

            </div>

        @endif
        </div>

    <style>
        #qrImg{
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
        #filedsetII div{
            float: right;
        }
        body{
            font-family: 'robotobold';
        }
        .col-md-12{
            width: 100%;
        }
        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
            #qrImg{
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