@extends('layouts.master_dashboard')
@section('title', trans('messages.tltTransactions'))

@section('content')
    <?php
    /**
     * Created by PhpStorm.
     * User: meysam
     * Date: 2/27/2018
     * Time: 1:16 PM
     */
    use Illuminate\Support\Facades\Log;
    ?>
    <div class="main-container container-fluid direction" >
        <div class="row-fluid" style="margin-bottom: 30px">
            <h3 class="header smaller lighter blue">لیست 1000 تراکنش آخر در یک ماه گذشته</h3>
            <div class="table-header">
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

            <form name="search-form" id="search-form" method="POST" action="{{ url('/transactions/search') }}">
                {{ csrf_field() }}

                @if(isset($user_id) && $user_id != null)
                    <input id="user_id" type="hidden" name="user_id" value="{{ $user_id }}"
                    >
                @endif

                @if(isset($tour_id) && $tour_id != null)
                    <input id="tour_id" type="hidden" name="tour_id" value="{{ $tour_id }}"
                    >
                @endif

                @if(isset($garden_id) && $garden_id != null)
                    <input id="garden_id" type="hidden" name="garden_id" value="{{ $garden_id }}"
                    >
                @endif

                <fieldset>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" class="btn01 btn btn-default section-btn" style="background-color: #62b0bc">
                                {{trans('messages.btnSearch')}}
                            </button>
                        </div>
                        <div class="col-sm-4">
                            <input id="end_date" type="text" name="end_date" value="{{ old('end_date') }}" autocomplete="off"
                                   placeholder="{{trans('messages.lblEndDate')}}">
                            <div class="clear"></div>
                        </div>
                        <div class="col-sm-4">
                            <input id="start_date" type="text" name="start_date" value="{{ old('start_date') }}" autocomplete="off"
                                   placeholder="{{trans('messages.lblStartDate')}}" autofocus>
                            <div class="clear"></div>
                        </div>


                    </div>
                </fieldset>


            </form>


            {{--<div class="table-header">--}}

            {{--</div>--}}




        </div>


        <div style="overflow-x:auto;">
            <table id="sample-table-2" class="table table-striped table-bordered table-hover" style="padding: 50px;">
                <thead>
                <tr>
                    <th>{{trans('messages.lblAmount')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblType')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblDate')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblDescription')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblAuthority')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblDigitalReceipt')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblStatus')}}</th>
                    <th>{{trans('messages.lblTransactionResult')}}</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @if(isset($transactions))
                    @foreach($transactions as $transaction)
                        <?php
                        $type = "";
                        if (isset($transaction->info->garden_id))
                            $type = trans('messages.tltGarden');
                        else
                            $type = trans('messages.tltTour');

                        $carbon = new \Carbon\Carbon($transaction->created_at);
                        $year = $carbon->year;
                        $month = $carbon->month;
                        $day = $carbon->day;
                        $jdf = new \App\Utilities\JDF();
                        $date = $jdf->gregorian_to_jalali($year, $month, $day, '/');
                        $time = $carbon->toTimeString();
                        $date = $date . " " . $time;
                        ?>
                        <tr>
                            <td>{{number_format($transaction->amount)}} تومان</td>
                            <td>{{$type}}</td>
                            <td>{{$date}}</td>
                            <td>{{$transaction->description}}</td>
                            <td>{{$transaction->authority}}</td>
                            <td>{{$transaction->transaction_guid}}</td>
                            <td>
                                @if($transaction->type == \App\Transaction::TRANSACTION_STATUS_NOT_SETTLED)
                                    <span style="color:red"> {{\App\Transaction::getMessage($transaction->type)}}</span>

                                @else
                                    <span style="color:green"> {{\App\Transaction::getMessage($transaction->type)}}</span>

                                @endif
                            </td>
                            <td>
                                @if($transaction->reserve_id != null)
                                    <span style="color:green"> {{\App\Transaction::getMessage($transaction->status)}}</span>

                                @else
                                    <span style="color:red"> {{trans('messages.msgOperationFailed')}}</span>

                                @endif

                            </td>
                            <td class="td-actions">
                                <div class="hidden-phone visible-desktop action-buttons">
                                    <a class="blue"
                                       href="{{url("/transactions/show/{$transaction->transaction_id}/{$transaction->transaction_guid}")}}">
                                        <i class="icon-zoom-in bigger-130"></i>{{trans('messages.btnDetails')}}
                                    </a>
                                    @if((Auth::user()->type != \App\User::TypeAdmin || Auth::user()->type != \App\User::TypeOperator) && $transaction->type != \App\Transaction::TRANSACTION_STATUS_SETTLED)
                                        <a class="blue"
                                           data-rel="tooltip" onclick="settleQuestion('{{$transaction->transaction_id}}','{{$transaction->transaction_guid}}')" />
                                        <i class="icon-money bigger-130"></i>{{trans('messages.btnSettle')}}
                                        </a>
                                    @endif
                                </div>

                                <div class="hidden-desktop visible-phone">
                                    <div class="inline position-relative">
                                        <button class="btn btn-minier btn-yellow dropdown-toggle"
                                                data-toggle="dropdown">
                                            <i class="icon-caret-down icon-only bigger-120"></i>
                                        </button>

                                        <ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
                                            <li>
                                                <a class="tooltip-info"
                                                   href="{{url("/transactions/show/{$transaction->transaction_id}/{$transaction->transaction_guid}")}}"
                                                   data-rel="tooltip" title={{trans('messages.btnDetail')}}>
																	<span class="blue">
																		<i class="icon-zoom-in bigger-120"></i>
																	</span>{{trans('messages.btnDetails')}}
                                                </a>
                                                @if((Auth::user()->type != \App\User::TypeAdmin || Auth::user()->type != \App\User::TypeOperator) && $transaction->type != \App\Transaction::TRANSACTION_STATUS_SETTLED)
                                                    <a class="blue" data-rel="tooltip" onclick="settleQuestion('{{$transaction->transaction_id}}','{{$transaction->transaction_guid}}')"
                                                    >
                                                        <i class="icon-money bigger-130"></i>{{trans('messages.btnSettle')}}
                                                    </a>
                                                @endif
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

        <div>
            جمع مبالغ : {{number_format($totalCost)}} {{trans('messages.lblToman')}}
        </div>

        @if(isset($totalSiteShare))
            <div>
                جمع سهم سایت : {{$totalSiteShare}} {{trans('messages.lblToman')}}
            </div>
        @endif
    </div>
    <script type="text/javascript">
        $(function () {
            $('#start_date').persianDatepicker({
                minDate: new persianDate().unix(),
                initialValue: false,
                altField: '#start_date',
                altFormat: 'L',
                autoClose: true,
                format: 'dddd, DD MMMM YYYY',
                toolbox: {
                    calendarSwitch: {
                        enabled: false
                    }
                }
            });
        });
        $(function () {
            $('#end_date').persianDatepicker({
                minDate: new persianDate().unix(),
                initialValue: false,
                altField: '#end_date',
                altFormat: 'L',
                autoClose: true,
                format: 'dddd, DD MMMM YYYY',
                toolbox: {
                    calendarSwitch: {
                        enabled: false
                    }
                }
            });
        });
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
        })

        function settleQuestion(transaction_id,transaction_guid)
        {
            swal({
                title: '{!! trans('messages.msgAreYouSure') !!}',
                text: '{!! trans('messages.msgCantRevert') !!}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{!! trans('messages.lblYes') !!}',
                cancelButtonText: '{!! trans('messages.lblNo') !!}'

            }).then((result) => {
                if (result.value) {
                    // alert('ddd: user id'+user_id);

                    var urlx = '{!! url('/transactions/settle') !!}'+'/'+transaction_id+'/'+transaction_guid;
                    document.location = urlx;
                }
            })
        }
    </script>
@endsection