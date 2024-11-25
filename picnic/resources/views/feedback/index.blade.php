@extends('layouts.master_dashboard')
@section('title', trans('messages.tltFeedBack'))

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

    <div class="main-container container-fluid direction">
        <div class="row-fluid" style="margin-bottom: 30px;">
            <h3 class="header smaller lighter blue">{{trans('messages.tltFeedBack')}}</h3>
            <div class="table-header">
                @if(count($errors) > 0)
                    @foreach($errors as $error)
                        <ul style="color: darkred">
                            <li style="text-align: center" colspan="2">{{$error}}</li>
                        </ul>
                    @endforeach
                    {{ session()->forget('errors')}}
                @endif

                @if(session('messages'))
                    @foreach (session('messages') as $message)
                        <ul style="color: green">
                            <li style="text-align: center" colspan="2">{{ $message}}</li>
                        </ul>
                    @endforeach
                    {{ session()->forget('messages')}}
                @endif
            </div>

        </div>
        <div class="table-header">
            @if(isset($messages))
                @foreach ($messages->all() as $message)
                    @if (in_array($message->Code, \App\OperationMessage::RedMessages))
                        <tr style="color: darkred;">
                            <th style="text-align: center" colspan="2">{{$message->Text}}</th>

                        </tr>
                    @else
                        <tr style="color: green">
                            <th style="text-align: center" colspan="2">{{ $message->Text}}</th>
                        </tr>
                    @endif
                @endforeach
            @endif
        </div>

        <div style="overflow-x:auto;">
            <table id="sample-table-2" class="table table-striped table-bordered table-hover"  style="padding: 50px;">
                <thead>
                <tr>
                    <th>{{trans('messages.lblTitle')}}</th>
                    <th class="hidden-phone">{{trans('messages.lblDescription')}}</th>
                    <th>{{trans('messages.lblDate')}}</th>
                    <th></th>
                </tr>
                </thead>


                <tbody>
                @if(isset($feedbacks))
                    @foreach($feedbacks as $feedback)
                        <tr>
                            <td>{{$feedback->title}}</td>
                            <td class="hidden-phone">{{$feedback->description}}</td>

                            <td>{{jDate::forge($feedback->created_at)->format('Y/m/d')}}</td>


                            <td class="td-actions">
                                <div class="hidden-phone visible-desktop action-buttons">
                                    <a class="blue" href="{{url("/feedbacks/detail/{$feedback->feedback_id}/{$feedback->feedback_guid}")}}">
                                        <i class="icon-zoom-in bigger-130"></i>
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
                                                <a class="tooltip-info"
                                                   href="{{url("/feedbacks/detail/{$feedback->feedback_id}/{$feedback->feedback_guid}")}}"
                                                   data-rel="tooltip" title={{trans('messages.btnDetail')}}>
																	<span class="blue">
																		<i class="icon-zoom-in bigger-120"></i>
																	</span>
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
    {{--script for dataTable--}}
    <script type="text/javascript">
        $(function() {
            var oTable1 = $('#sample-table-2').dataTable( {
                "aoColumns": [
                    null,
                    null,
                    null,
                    { "bSortable": false }
                ] } );


            $('table th input:checkbox').on('click' , function(){
                var that = this;
                $(this).closest('table').find('tr > td:first-child input:checkbox')
                    .each(function(){
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

                if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
                return 'left';
            }
        })
    </script>

    {{--<div id="15265584439806729"><script type="text/JavaScript" src="https://www.aparat.com/embed/hFOMz?data[rnddiv]=15265584439806729&data[responsive]=yes"></script></div>--}}
    {{--<div id="15265586739867153"><script type="text/JavaScript" src="https://www.aparat.com/embed/5NnB2?data[rnddiv]=15265586739867153&data[responsive]=yes"></script></div>--}}
    {{--<div id="15265587167874741"><script type="text/JavaScript" src="https://www.aparat.com/embed/qkbza?data[rnddiv]=15265587167874741&data[responsive]=yes"></script></div>--}}

@endsection