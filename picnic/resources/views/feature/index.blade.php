@extends('layouts.master_dashboard')
@section('title', 'لیست ویژگی ها')

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
            <h3 class="header smaller lighter blue">ویژگی ها</h3>
            <div style="margin: 2%;"><a href="{{url('/features/create')}}">افزودن ویژگی جدید</a></div>

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
                    <th>نام</th>
                    <th class="hidden-phone">توضیحات</th>
                    <th></th>
                </tr>
                </thead>


                <tbody>
                @if(isset($features))
                    @foreach($features as $feature)

                        <tr>
                            <td>{{$feature->name}}</td>
                            <td class="hidden-phone">{{str_limit($feature->description, $limit = 10, $end = '...')}}</td>

                            <td class="td-actions">
                                <a class="blue" href="{{url("/features/detail/{$feature->feature_id}/{$feature->feature_guid}")}}">
                                    <i class="icon-zoom-in bigger-130"></i>
                                </a>
                                <a class="blue" href="{{url("/features/edit/{$feature->feature_id}/{$feature->feature_guid}")}}">
                                    <i class="icon-pencil bigger-130"></i>
                                </a>
                                <a class="blue" onclick="removeFunction('{{$feature->feature_id}}','{{$feature->feature_guid}}');">
                                    <i class="icon-remove bigger-130"></i>
                                </a>

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

        function removeFunction(feature_id, feature_guid) {

            swal({
                title: 'آیا اطمینان دارید؟',
                text: 'این عملیات قابل بازگشت نمی باشد',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'خیر'

            }).then((result) => {
                if (result.value) {
                    // alert('ddd: user id'+user_id);

                    var urlx = '{!! url('/features/remove') !!}'+'/'+feature_id+'/'+feature_guid;
                    document.location = urlx;
                }
            })
        }


        $(function() {
            var oTable1 = $('#sample-table-2').dataTable( {
                "aoColumns": [
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

@endsection