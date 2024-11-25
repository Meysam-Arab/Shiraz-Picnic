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
        <legend>لیست 500 رزرو آخر تمامی باغ ها و تورهای شما</legend>
    </fieldset>
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


        <div class="row">
            <div class="col-md-6">
                {{trans('messages.lblCount')}} : {{$total_count}}
            </div>
            <div class="col-md-6 no-print">
                <a onclick="printFunction()" class="btn btn-primary no-print"> <i class="icon-print bigger-120"></i></a>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table id="sample-table-2" class="table table-striped table-bordered table-hover"  style="padding: 50px;">
                <thead>
                <tr>
                    <th>{{trans('messages.lblStatus')}}</th>
                    <th>{{trans('messages.lblType')}}</th>
                    <th>{{trans('messages.lblStartDate')}}</th>
                    <th>{{trans('messages.lblEndDate')}}</th>
                    <th>{{trans('messages.lblNameAndFamily')}}</th>
                    <th>{{trans('messages.lblNationalCode')}}</th>
                    <th>{{trans('messages.lblTitle')}}</th>
                    <th>{{trans('messages.lblAddress')}}</th>
                    <th class="no-print">{{trans('messages.btnOperation')}}</th>
                </tr>
                </thead>

                <tbody>
                @if(isset($reservs))
                    @foreach($reservs as $reserve)
                        <?php
                        $carbon = new Carbon($reserve->start_date);
                        $year = $carbon->year;
                        $month = $carbon->month;
                        $day = $carbon->day;
                        $jdf = new \App\Utilities\JDF();
                        $startDate = $jdf->gregorian_to_jalali($year, $month, $day, '/');

                        $carbon = new Carbon($reserve->end_date);
                        $year = $carbon->year;
                        $month = $carbon->month;
                        $day = $carbon->day;
                        $jdf = new \App\Utilities\JDF();
                        $endDate = $jdf->gregorian_to_jalali($year, $month, $day, '/');


                        $type = "";
                        if(isset($reserve->info->garden_id))
                            $type = trans('messages.tltGarden');
                        else
                            $type = trans('messages.tltTour');

                        ?>
                        <tr>
                            <td>{{\App\Reserve::getStatusStringByCode($reserve->status)}}</td>
                            <td>{{$type}}</td>
                            <td>{{$startDate}}</td>
                            <td>{{$endDate}}</td>
                            <td>
                                {{$reserve->name_and_family}}

                            </td>
                            <td>
                                {{$reserve->national_code}}
                            </td>
                            <td>
                                {{$reserve->title}}
                            </td>
                            <td>
                                {{$reserve->address}}
                            </td>


                            <td class="td-actions no-print">
                                <div class="hidden-phone visible-desktop action-buttons">
                                    <a class="blue" target="_blank"
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
        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>
@endsection