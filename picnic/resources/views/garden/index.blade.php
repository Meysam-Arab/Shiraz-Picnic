@extends('layouts.master_dashboard')
@section('title', trans('messages.tltGardens'))

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
    <div class="main-container container-fluid direction">
        <div class="row-fluid" style="margin-bottom: 30px">
            <h3 class="header smaller lighter blue">{{trans('messages.tltGardens')}}</h3>
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
            {{--<div class="table-header">--}}

            {{--</div>--}}


        </div>

        <div style="overflow-x:auto;">
            <table id="sample-table-2" class="table table-striped table-bordered table-hover"  style="padding: 50px;">
                <thead>
                <tr>
                    <th>{{trans('messages.lblGardenName')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblAddress')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblOperation')}}</th>
                </tr>
                </thead>

                <tbody>
                @if(isset($gardens))
                    @foreach($gardens as $garden)
                        <tr>
                            <td>{{$garden->name}}</td>
                            <td>{{$garden->address}}</td>
                            <td class="td-actions">
                                <div class="hidden-phone visible-desktop action-buttons">
                                    <a class="blue"
                                       href="{{url("/gardens/detail/{$garden->garden_id}")}}">
                                        <i class="icon-zoom-in bigger-130"></i>{{trans('messages.btnDetails')}}
                                    </a>
                                    <a class="blue"
                                       href="{{url("/gardens/reservs/{$garden->garden_id}/{$garden->garden_guid}/0/10")}}">
                                        <i class="icon-zoom-in bigger-130"></i>{{trans('messages.btnReserves')}}
                                    </a>
                                    @if(Auth::user()->type != \App\User::TypeGuard)
                                        <a class="blue"
                                           href="{{url("/gardens/transactions/{$garden->garden_id}/{$garden->garden_guid}")}}">
                                            <i class="icon-zoom-in bigger-130"></i>{{trans('messages.btnTransactions')}}
                                        </a>
                                    @endif
                                    {{--@if( Auth::user()->type == \App\User::TypeOperator || Auth::user()->type == \App\User::TypeAdmin)--}}
                                    {{--<a class="blue"--}}
                                    {{--href="{{url("/gardens/edit/{$garden->garden_id}/{$garden->garden_guid}")}}">--}}
                                    {{--<i class="icon-zoom-in bigger-130"></i>{{trans('messages.btnEdit')}}--}}
                                    {{--</a>--}}
                                    {{--<a class="blue"--}}
                                    {{--href="{{url("/gardens/remove/{$garden->garden_id}/{$garden->garden_guid}")}}">--}}
                                    {{--<i class="icon-zoom-in bigger-130"></i>{{trans('messages.btnDelete')}}--}}
                                    {{--</a>--}}
                                    {{--@endif--}}

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
                                                   href="{{url("/gardens/detail/{$garden->garden_id}")}}"
                                                   data-rel="tooltip" title={{trans('messages.btnDetail')}}>
																	<span class="blue">
																		<i class="icon-zoom-in bigger-120"></i>
																	</span>{{trans('messages.btnDetails')}}
                                                </a>

                                                <a class="tooltip-info"
                                                   href="{{url("/gardens/reservs/{$garden->garden_id}/{$garden->garden_guid}/0/10")}}"
                                                   data-rel="tooltip" title={{trans('messages.btnDetail')}}>
																	<span class="blue">
																		<i class="icon-zoom-in bigger-120"></i>
																	</span>{{trans('messages.btnReserves')}}
                                                </a>
                                                @if(Auth::user()->type != \App\User::TypeGuard)
                                                    <a class="tooltip-info"
                                                       href="{{url("/gardens/transactions/{$garden->garden_id}/{$garden->garden_guid}")}}"
                                                       data-rel="tooltip" title={{trans('messages.btnDetail')}}>
																	<span class="blue">
																		<i class="icon-zoom-in bigger-120"></i>
																	</span>{{trans('messages.btnTransactions')}}
                                                    </a>
                                                @endif

                                                {{--@if( Auth::user()->type == \App\User::TypeOperator || Auth::user()->type == \App\User::TypeAdmin)--}}

                                                {{--<a class="tooltip-info"--}}
                                                {{--href="{{url("/gardens/edit/{$garden->garden_id}/{$garden->garden_guid}")}}"--}}
                                                {{--data-rel="tooltip" title={{trans('messages.btnDetail')}}>--}}
                                                {{--<span class="blue">--}}
                                                {{--<i class="icon-zoom-in bigger-120"></i>--}}
                                                {{--</span>{{trans('messages.btnEdit')}}--}}
                                                {{--</a>--}}
                                                {{--<a class="tooltip-info"--}}
                                                {{--href="{{url("/gardens/remove/{$garden->garden_id}/{$garden->garden_guid}")}}"--}}
                                                {{--data-rel="tooltip" title={{trans('messages.btnDetail')}}>--}}
                                                {{--<span class="blue">--}}
                                                {{--<i class="icon-zoom-in bigger-120"></i>--}}
                                                {{--</span>{{trans('messages.btnDelete')}}--}}
                                                {{--</a>--}}
                                                {{--@endif--}}
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
    </script>
@endsection