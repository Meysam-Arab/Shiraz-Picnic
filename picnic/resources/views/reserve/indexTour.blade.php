@extends('layouts.master_dashboard')
@section('title', trans('messages.tltDashboard'))
@section('content')
    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;
    use  \App\Utilities\JDF;


    ?>
    <fieldset>
        <legend> لیست رزرو های {{$tour->title}}</legend>

    </fieldset>
    <div class="row">
        <div class="col-md-12">
        <div class="col-md-6 no-print">
            <a style="float: left" onclick="printFunction()" class="btn btn-primary no-print"> <i class="icon-print bigger-120"></i></a>
        </div>
        <div class="col-md-6 no-print">
            {{--<a onclick="printFunction()" class="btn btn-primary no-print"> <i class="icon-print bigger-120"></i></a>--}}
        </div>
        </div>
    </div>

    <div class="col-md-12 no-print">
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
        @if(isset($messages))
            @foreach ($messages->all() as $message)
                @if (in_array($message->Code, \App\OperationMessage::RedMessages))
                    <tr style="color: darkred;">
                        <th style="text-align: center" colspan="2">{{$message->Text}}</th>

                    </tr>
                @else
                    <tr style="color: green">
                        <th style="text-align: center" colspan="3">{{ $message->Text}}</th>
                    </tr>
                @endif
            @endforeach
        @endif
    </div>


    <div class="col-md-12 card-deck" style="margin-top: 10px;margin-bottom: 20px;">


        @if(isset($total_optional_info->features))

            @if( count($total_optional_info->features))
                <div class="row">
                    @php
                        $count_TD = 0;

                    @endphp
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="text-align: center">{{trans('messages.lblTitle')}}</th>
                            <th style="text-align: center">{{trans('messages.lblNumber')}}</th>
                            <th style="text-align: center">{{trans('messages.lblTitle')}}</th>
                            <th style="text-align: center">{{trans('messages.lblNumber')}}</th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach($total_optional_info->features as $feature)
                            @if($feature->is_optional)
                                @php
                                    $count_TD++;

                                @endphp
                                @if($count_TD == 1)
                                    <tr>
                                        <td>
                                            {{$feature->name}}
                                        </td>
                                        <td>
                                            {{$feature->count}}
                                        </td>
                                @else
                                            <td>
                                                {{$feature->name}}
                                            </td>
                                            <td>
                                                {{$feature->count}}
                                            </td>

                                    </tr>
                                    @php
                                        $count_TD = 0;

                                    @endphp
                                @endif

                            @endif
                        @endforeach

                        @if($count_TD > 1)
                                </tr>
                        @endif

                        </tbody>
                    </table>


                </div>
            @endif
        @endif



        <div class="row" style="margin-top: 15px">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="background: #fb6e14;    border: 2px solid #fb6e14; border-radius: 4px;">
                تعداد کل افراد ثبت نامی : {{$total_count}}
            </div>
            <div class="col-md-4"> درصد سایت:{{$tour->info->site_share}} درصد </div>

            {{--<div class="col-md-6 no-print">--}}
            {{--<a onclick="printFunction()" class="btn btn-primary no-print"> <i class="icon-print bigger-120"></i></a>--}}
            {{--</div>--}}
        </div>
        <br>

            <div style="overflow-x:auto;">
                <table id="sample-table-2" class="table table-striped table-bordered table-hover"  style="padding: 50px;">
                    <thead>
                    <tr>
                        <th>{{trans('messages.lblStatus')}}</th>
                        <th>{{trans('messages.lblNameAndFamily')}}</th>
                        <th>{{trans('messages.lblNationalCode')}}</th>
                        <th>{{trans('messages.lblMobile')}}</th>
                        <th>{{trans('messages.lblBirthDate')}}</th>
                        <th>{{trans('messages.lblCompanionsCount')}}</th>
                        <th>{{"مبلغ"." "."(تومان)"}}</th>
                        <th class="no-print">{{trans('messages.btnOperation')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if(isset($reservs))
                        @foreach($reservs as $reserve)
                            <tr>
                                <td>{{\App\Reserve::getStatusStringByCode($reserve->status)}}</td>
                                <td>
                                    @foreach($reserve->info->persons as $person)
                                        {{$person->name_and_family."-"}}
                                    @endforeach

                                </td>
                                <td>
                                    @foreach($reserve->info->persons as $person)
                                        {{$person->national_code."-"}}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($reserve->info->persons as $person)
                                        {{$person->mobile."-"}}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($reserve->info->persons as $person)
                                        {{$person->birth_date."-"}}
                                    @endforeach
                                </td>
                                <td>{{count($reserve->info->persons) - 1}}</td>
                                <td>{{number_format($reserve->amount)}}</td>
                                <td class="td-actions no-print">
                                    <div class="hidden-phone visible-desktop action-buttons">
                                        <a class="blue"  target="_blank"
                                           href="{{url("/reservs/detail/{$reserve->reserve_id}/{$reserve->reserve_guid}")}}"
                                           title="{{trans('messages.btnReserveDetails')}}">
                                            <i class="icon-zoom-in bigger-130"></i>{{trans('messages.btnReserveDetails')}}
                                        </a>
                                    </div>

                                    <div class="hidden-desktop visible-phone">
                                        <div class="inline position-relative">
                                            <button class="btn btn-minier btn-yellow dropdown-toggle"
                                                    data-toggle="dropdown">
                                                <i class="icon-caret-down icon-only bigger-120"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
                                                <li>
                                                    <a class="tooltip-info"  target="_blank"
                                                       href="{{url("/reservs/detail/{$reserve->reserve_id}/{$reserve->reserve_guid}")}}"
                                                       data-rel="tooltip" title={{trans('messages.btnReserveDetails')}}>
																	<span class="blue">
																		<i class="icon-zoom-in bigger-120"></i>
																	</span>{{trans('messages.btnReserveDetails')}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    @endif

                    </tbody>
                </table>
            </div>


    </div>
    <script type="text/javascript">
        $(function () {
            var oTable1 = $('#sample-table-2').dataTable({
                "aoColumns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    {"bSortable": false}
                ]
            });


            $('table th input:checkbox').on('click', function () {
                var that = this;
                $(this).closest('table').find('tr > td:first-child input:checkbox')
                    .each(function () {
                        this.checked = that.checked;
                        $(this).closest('tr').toggleClass('selected');
                    });

            });


            $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});

            function tooltip_placement(context, source) {
                var $source = $(source);
                var $parent = $source.closest('table')
                var off1 = $parent.offset();
                var w1 = $parent.width();

                var off2 = $source.offset();
                var w2 = $source.width();

                if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
                return 'left';
            }

            $(".row-fluid").addClass("no-print");
        })
    </script>
    <script>
        function printFunction() {
            window.print();
        }
    </script>
    <style>
        @media print {
            .no-print, .no-print * {
                display: none !important;
            }
        }
    </style>
@endsection