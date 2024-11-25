@extends('layouts.master_dashboard')
@section('title', trans('messages.tltUsersList'))

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
        <div class="row-fluid" style="margin-bottom: 30px; ">
            <h3 class="header smaller lighter blue">{{trans('messages.tltUsersList')}}</h3>
            <div style="margin: 2%;"><a href="{{url('/users/create')}}">افزودن کاربر جدید</a></div>
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

            <div style="overflow-x:auto;">
                <table id="sample-table-2" class="table table-striped table-bordered table-hover"  style="padding: 50px;">
                    <thead>
                    <tr>
                        <th>{{trans('messages.lblNameAndFamily')}}</th>
                        <th>{{trans('messages.lblType')}}</th>
                        <th>{{trans('messages.lblStatus')}}</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @if(isset($users))

                        @foreach($users as $user)
                            <tr>
                                <td class="center">
                                    {{$user->name_family}}
                                </td>

                                <td>
                                    @if($user->type == \App\User::TypeAdmin)
                                        <span class="label label-inverse">{{trans('messages.txtUserTypeAdmin')}}</span>
                                    @elseif($user->type == \App\User::TypeOperator)
                                        <span class="label label-success">{{trans('messages.txtUserTypeOperator')}}</span>
                                    @elseif($user->type == \App\User::TypeGuard)
                                        <span class="label label-grey">{{trans('messages.txtUserTypeGuard')}}</span>
                                    @elseif($user->type == \App\User::TypeLeader)
                                        <span class="label label-purple">{{trans('messages.txtUserTypeLeader')}}</span>
                                    @else
                                        <span class="label label-info">{{trans('messages.txtUserTypeOwner')}}</span>
                                    @endif
                                </td>

                                <td class="center">
                                    {{ \App\User::getStatusStringByCode($user->status)}}
                                </td>

                                <td class="td-actions">
                                    <div class="hidden-phone visible-desktop action-buttons">
                                        <a class="blue" href="{{url("/users/detail/{$user->user_id}/{$user->user_guid}")}}">
                                            <i class="icon-zoom-in bigger-130">{{trans('messages.btnDetails')}}</i>
                                        </a>
                                        @if($user->type == \App\User::TypeOwner)
                                            <a class="purple" href="{{url("/transactions/index/{$user->user_id}")}}" target="_blank">
                                                <i class="icon-dollar bigger-130">{{trans('messages.btnTransactions')}}</i>
                                            </a>
                                        @endif
                                        @if(($user->type == \App\User::TypeOwner ||
                                            $user->type == \App\User::TypeGuard))
                                            <a class="light-orange" href="{{url("/gardens/list/{$user->user_id}")}}" target="_blank">
                                                <i class="icon-qrcode bigger-130">{{trans('messages.btnGardens')}}</i>
                                            </a>
                                        @endif

                                        @if(($user->type == \App\User::TypeOwner ||
                                           $user->type == \App\User::TypeLeader))
                                            <a class="light-green" href="{{url("/tours/list/{$user->user_id}")}}" target="_blank">
                                                <i class="icon-qrcode bigger-130">{{trans('messages.btnTours')}}</i>
                                            </a>
                                        @endif


                                        @if((Auth()->user()->type == \App\User::TypeAdmin ||
                                                 Auth()->user()->type == \App\User::TypeOperator ) && $user->type != \App\User::TypeAdmin)
                                            <a class="green"
                                               href="{{url("/users/edit/{$user->user_id}/{$user->user_guid}")}}">
                                                <i class="icon-pencil bigger-130">{{trans('messages.btnEdit')}}</i>
                                            </a>

                                            <a class="red"
                                               onclick="removeFunction('{{$user->user_id}}','{{$user->user_guid}}');">
                                                <i class="icon-trash bigger-130">{{trans('messages.btnDelete')}}</i>
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
                                                       href="{{url("/users/detail/{$user->user_id}/{$user->user_guid}")}}"
                                                       data-rel="tooltip" title={{trans('messages.btnDetail')}}>
																	<span class="blue">
																		<i class="icon-zoom-in bigger-120"></i>
																	</span>
                                                    </a>
                                                </li>
                                                @if($user->type == \App\User::TypeOwner)
                                                    <li>
                                                        <a class="tooltip-info"
                                                           href="{{url("/transactions/index/{$user->user_id}")}}"
                                                           data-rel="tooltip" title={{trans('messages.btnTransactions')}}>
																	<span class="purple">
																		<i class="icon-dollar bigger-120"></i>
																	</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(($user->type == \App\User::TypeOwner ||
                                                $user->type == \App\User::TypeGuard ))
                                                    <li>
                                                        <a class="tooltip-info"
                                                           href="{{url("/gardens/list/{$user->user_id}")}}" data-rel="tooltip"
                                                           title={{trans('messages.btn_gardens')}}>
																	<span class="light-orange">
																		<i class="icon-qrcode bigger-120"></i>
																	</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if((Auth()->user()->type == \App\User::TypeAdmin ||
                                                 Auth()->user()->type == \App\User::TypeOperator ) && $user->type != \App\User::TypeAdmin)
                                                    <li>
                                                        <a href="{{url("/users/edit/{$user->user_id}/{$user->user_guid}")}}"
                                                           class="tooltip-success" data-rel="tooltip"
                                                           title={{trans('messages.btn_edit')}}>
																	<span class="green">
																		<i class="icon-edit bigger-120"></i>
																	</span>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a
                                                                onclick="removeFunction('{{$user->user_id}}','{{$user->user_guid}}');"
                                                                class="tooltip-error" data-rel="tooltip"
                                                                title={{trans('messages.btnDelete')}}>
																	<span class="red">
																		<i class="icon-trash bigger-120"></i>
																	</span>
                                                        </a>
                                                    </li>
                                                @endif
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
    </div>

@endsection


@section('page-js-script')
    <script type="text/javascript">

        function removeFunction(user_id, user_guid) {


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

                    var urlx = '{!! url('/users/remove') !!}'+'/'+user_id+'/'+user_guid;
                    document.location = urlx;
                    // swal(
                    // 'Deleted!',
                    // 'Your file has been deleted.',
                    // 'success'
                    // )

                }
            })
        }

    </script>
@stop