@extends('layouts.master_dashboard')
@section('title',' لیست بلاگ ها')

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

        <div class="section-header">
            @if(count($errors) > 0)
                <div class="alert-error alert">
                    @foreach($errors as $error)
                        <ul style="color: darkred">
                            <li style="text-align: center" colspan="2">{{$error}}</li>
                        </ul>
                    @endforeach
                </div>
                {{ session()->forget('errors')}}
            @endif

            @if(session('messages'))
                <div class="alert-warning alert">
                    @foreach (session('messages') as $message)
                        <ul style="color: green">
                            <li style="text-align: center" colspan="2">{{ $message}}</li>
                        </ul>
                    @endforeach
                </div>
                {{ session()->forget('messages')}}
            @endif

        </div>
        </p>

        <div class="row-fluid" style="margin-bottom: 30px">
            <h3 class="header smaller lighter blue"> لیست بلاگ ها</h3>



        </div>

        <div style="overflow-x:auto;">
            <div class="table-header">
                <a target="_blank" style="color: black !important;font-weight: bold;margin: 0px 10px"
                   href="{{url("/blogs/create")}}"><i class="icon-plus bigger-130"></i>جدید</a>
            </div>
            <table id="sample-table-2" class="table table-striped table-bordered table-hover"  style="padding: 50px;">
                <thead>
                <tr>
                    <th>عنوان</th>
                    <th>نوع</th>
                    <th>تاریخ</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                @if(isset($blogs))
                    @foreach($blogs as $blog)
                        <?php
                        $carbon = new Carbon($blog->blog_date_time);
                        $year = $carbon->year;
                        $month = $carbon->month;
                        $day = $carbon->day;
                        $jdf = new \App\Utilities\JDF();
                        $date = $jdf->gregorian_to_jalali($year, $month, $day, '/');
                        ?>
                        <tr>
                            <td class="center">
                                {{$blog->title}}
                            </td>
                            {{--<td class="hidden-phone">{{$blog->description}}</td>--}}
                            @if($blog->type == \App\Blog::BLOG_TYPE_NEWS)
                                <td>اطلاع رسانی</td>
                            @else
                                <td>آموزشی</td>
                            @endif
                            <td>{{$date}}</td>
                            <td class="td-actions">
                                <div class="hidden-phone visible-desktop action-buttons">
                                    <a target="_blank" class="blue" href="{{url("/blogs/detail/{$blog->blog_id}")}}" data-rel="tooltip" title='جزییات'>
                                        <i class="icon-zoom-in bigger-130"></i>
                                    </a>

                                    <a target="_blank" class="green"
                                       href="{{url("/blogs/edit/{$blog->blog_id}/{$blog->blog_guid}")}}" data-rel="tooltip" title='ویرایش'>
                                        <i class="icon-pencil bigger-130"></i>
                                    </a>

                                    <a class="red"
                                       onclick="removeFunction('{{$blog->blog_id}}','{{$blog->blog_guid}}');" data-rel="tooltip" title='حذف'>
                                        <i class="icon-trash bigger-130"></i>
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
                                                <a target="_blank" class="tooltip-info"
                                                   href="{{url("/blogs/detail/{$blog->blog_id}")}}"
                                                   data-rel="tooltip" title='جزییات'>
																	<span class="blue">
																		<i class="icon-zoom-in bigger-120"></i>
																	</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a target="_blank" href="{{url("/blogs/edit/{$blog->blog_id}/{$blog->blog_guid}")}}"
                                                   class="tooltip-success" data-rel="tooltip"
                                                   title='ویرایش'>
																	<span class="green">
																		<i class="icon-edit bigger-120"></i>
																	</span>
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                        onclick="removeFunction('{{$blog->blog_id}}','{{$blog->blog_guid}}');"
                                                        class="tooltip-error" data-rel="tooltip"
                                                        title='حذف'>
																	<span class="red">
																		<i class="icon-trash bigger-120"></i>
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
        $(function () {
            var oTable1 = $('#sample-table-2').dataTable({
                "aoColumns": [
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

        function removeFunction(blog_id, blog_guid) {

            swal({
                title: 'آیا اطمینان دارید؟',
                text: 'این عملیات برگشت پذیر نمی باشد!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله',
                cancelButtonText: 'خیر'

            }).then((result) => {
                if (result.value) {
                    // alert('ddd: user id'+user_id);

                    var urlx = '{!! url('/blogs/remove') !!}'+'/'+blog_id+'/'+blog_guid;
                    document.location = urlx;
                }
            })
        }
    </script>

@endsection