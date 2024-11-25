<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="{{URL::to('assets-datatable/bootstrap.min.css')}}" rel="stylesheet" />
<link href="{{URL::to('assets-datatable/bootstrap-responsive.min.css')}}" rel="stylesheet" />
<link href="{{URL::to('assets-datatable/ace.min.css')}}" rel="stylesheet" />
<link href="{{URL::to('assets-datatable/ace-responsive.min.css')}}" rel="stylesheet" />
<link href="{{URL::to('assets-datatable/font-awesome.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{URL::to('css/mainPage/icomoon.css')}}">
<link rel="stylesheet" href="{{URL::to('css/mainPage/bootstrap.css')}}">
<link rel="stylesheet" href="{{URL::to('css/mainPage/style.css')}}">
<link href="{{URL::to('assets-pDatePicker/persian-datepicker.min.css')}}" rel="stylesheet">
<script src="{{URL::to('assets-switalert/sweetalert2.all.min.js')}}"></script>
<script src="{{URL::to('js/mainPage/jquery.min.js')}}"></script>
<script src="{{URL::to('js/mainPage/bootstrap.min.js')}}"></script>
<script src="{{URL::to('assets-datatable/jquery.dataTables-fa.min.js')}}"></script>
<script src="{{URL::to('assets-datatable/jquery.dataTables.bootstrap.js')}}"></script>
<script src="{{URL::to('assets-pDatePicker/persian-date.min.js')}}"></script>
<script src="{{URL::to('assets-pDatePicker/persian-datepicker.min.js')}}"></script>
<div class="container" style="direction:rtl;margin-top: 40px">
    <div class="row">
        <div class="col-md-12">
            <div class="col-sm-3 col-md-3 direction sidenavNew no-print" style="float: right">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed"><span class="glyphicon glyphicon-bookmark">
                            </span>{{trans('messages.btnSite')}}</a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <table class="table">
                                    <tbody>

                                    <tr>
                                        <td>
                                            <span class="glyphicon glyphicon-home text-primary"></span><a href={{url('/')}}>{{trans('messages.btnHome')}}</a>
                                        </td>
                                    </tr>
                                    </tbody></table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed"><span class="glyphicon glyphicon-qrcode">
                            </span>{{trans('messages.btnInformation')}}</a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" style="height: 0px;">
                            <div class="panel-body">
                                <table class="table">
                                    <tbody>
                                    {{--<tr>--}}
                                        {{--<td>--}}
                                            {{--<a href={{url('reservs/index/0/10')}}>{{trans('messages.btnReserves')}}</a>--}}
                                        {{--</td>--}}
                                    {{--</tr>--}}
                                    @if( Auth::user()->type != \App\User::TypeLeader)

                                        <tr>
                                        <td>
                                            <a href={{url('gardens/index')}}>{{trans('messages.btnGardens')}}</a>
                                        </td>
                                    </tr>
                                    @endif
@if( Auth::user()->type != \App\User::TypeGuard)
<tr>
    <td>
        <a href={{url('tours/index')}}>{{trans('messages.btnTours')}}</a>
    </td>
</tr>
@endif
@if(Auth::user()->type == \App\User::TypeAdmin)
    <tr>
        <td>
            <a href={{url('transactions/indexAdmin')}}>{{trans('messages.btnTransactions')}}</a>
        </td>
    </tr>
@endif
@if( Auth::user()->type == \App\User::TypeOperator || Auth::user()->type == \App\User::TypeAdmin)
    <tr>
        <td>
            <a href={{url('feedbacks/index')}}>{{trans('messages.btnFeedbacks')}}</a>
        </td>
    </tr>
    <tr>
        <td>
            <a href={{url('features/index')}}>ویژگی ها</a>
        </td>
    </tr>
    <tr>
        <td>
            <a href={{url('reports/index')}}>گزارش ها</a>
        </td>
    </tr>
@endif
@if(Auth::user()->type == \App\User::TypeOwner)
    <tr>
        <td>
            <a href={{url('transactions/list/'. Auth::user()->user_id)}}>{{trans('messages.btnTransactions')}}</a>
        </td>
    </tr>

@endif


@if( Auth::user()->type == \App\User::TypeOperator || Auth::user()->type == \App\User::TypeAdmin)
    <tr>
        <td>
            <a href={{url('users/index')}}>{{trans('messages.btnUsers')}}</a>
        </td>
    </tr>
    <tr>
        <td>
            <a href={{url('blogs/index')}}>بلاگ ها</a>
        </td>
    </tr>
@endif
<tr>
    <td>
        <a href={{url('users/logout')}}>{{trans('messages.btnLogout')}}</a>
    </td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
<div class="col-sm-9 col-md-9 direction" style="float: right">
@yield('content')
</div>
</div>
</div>
</div>


@yield('page-js-files')
@yield('page-js-script')