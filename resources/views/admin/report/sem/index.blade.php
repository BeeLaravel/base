@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/gritter/css/jquery.gritter.css')}}"><!-- jquery.gritter 弹窗 -->
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/bootstrap-sweetalert-master/dist/sweetalert.css')}}"><!-- bootstrap-sweetalert 弹窗 -->
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/bootstrap-datetimepicker1/css/bootstrap-datetimepicker.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/DataTables/extensions/Buttons/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css')}}">

    <style type="text/css">
        table.table > thead th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{url('admin/')}}">首页</a></li>
        <li><a href="{{url($parent_link)}}">{{$parent_title}}</a></li>
        <li class="active">{{$title}}</li>
    </ol>
    <h1 class="page-header">{{$title}} <small>{{$description}}</small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse" data-sortable-id="table-basic-5">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                    <h4 class="panel-title">列表</h4>
                </div>
                <div class="panel-body">
                    @if ( auth('admin')->user()->can('report_sem.add') )
                        <a href="{{ url($link.'/create') }}" style="pull-right">
                            <button type="button" class="btn btn-primary m-r-5 m-b-5"><i class="fa fa-plus-square-o"></i> 提交</button>
                        </a>
                    @endif
                    <form action="" method="POST" class="form-inline" style="margin-bottom: 5px; float: right;">
                        <div class="form-group">
                            <input type='checkbox' class="form-control" name="statistics" value="{{$search['statistics']}}" id='statistics' @if ( $search['statistics'] ) checked="checked" @endif style="zoom: 1.3;" />
                            <label class="control-label" for="statistics">汇总</label>
                        </div>&nbsp;&nbsp;
                        <div class="form-group">
                            <label class="control-label">日期：</label>
                            <input type='text' class="form-control date" name="date" value="{{$search['date']}}" placeholder="年/月/日" id='date' />
                            <input type='text' class="form-control date" name="last_date" value="{{$search['last_date']}}" placeholder="年/月/日" id='last_date' />
                        </div>&nbsp;&nbsp;
                        @if ( in_array(auth('admin')->user()->id, [0, 1]) )
                            <div class="form-group">
                                <label class="control-label">公司：</label>
                                <select name="corporation_id" class="form-control">
                                    <option value="">所有</option>
                                    @foreach ( $corporations as $corporation )
                                        <option value="{{$corporation['id']}}" @if ( $search['corporation_id']==$corporation['id'] ) selected="selected" @endif>{{$corporation['description']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </form>
                    <table class="table table-bordered table-hover responsive dt-responsive" id="datatable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="min-width: 90px;">日期</th>
                                <th rowspan="2" style="min-width: 30px;">单位</th>
                                <th colspan="2">费用</th>
                                <th colspan="4">对话</th>
                                <th colspan="3">预约</th>
                                <th colspan="3">到诊</th>
                            </tr>
                            <tr>
                                <th style="min-width: 30px;">消费</th>
                                <th style="min-width: 30px;">实际<br>消费</th>
                                <th style="min-width: 30px;">有效<br>对话</th>
                                <th style="min-width: 45px;">有效对<br>话成本</th>
                                <th style="min-width: 30px;">无效<br>对话</th>
                                <th style="min-width: 45px;">无效对<br>话占比</th>
                                <th style="min-width: 30px;">预约<br>数</th>
                                <th style="min-width: 30px;">预约<br>率</th>
                                <th style="min-width: 30px;">预约<br>成本</th>
                                <th style="min-width: 30px;">到诊<br>数</th>
                                <th style="min-width: 30px;">到诊<br>率</th>
                                <th style="min-width: 30px;">到诊<br>成本</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/gritter/js/jquery.gritter.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/bootstrap-sweetalert-master/dist/sweetalert.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/moment/moment-with-locales.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/bootstrap-datetimepicker1/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/media/js/jquery.dataTables.min.js') }}"></script><!-- dataTables -->
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/media/js/dataTables.bootstrap.min.js') }}"></script><!-- dataTables.bootstrap -->
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script><!-- dataTables.responsive -->

    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js') }}"></script><!-- buttons.html5 -->

    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Buttons/js/buttons.colVis.min.js') }}"></script><!-- buttons.colVis -->
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Buttons/js/jszip.min.js') }}"></script><!-- jszip -->
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js') }}"></script><!-- pdf -->
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js') }}"></script><!-- vfs_fonts -->
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js') }}"></script><!-- print -->

    <script type="text/javascript" src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>
    <script type="text/javascript">
        var url = '{!!$link!!}';
    </script>
    <script type="text/javascript" src="{{asset('statics/admin/scripts/report/sem.js')}}"></script>

    <script type="text/javascript" >
        $(function(){
            App.init();

            @if ( session()->has('flash_notification.message') )
                $.gritter.add({
                    title: '操作消息！',
                    text: '{!! session('flash_notification.message') !!}'
                });
            @endif
        });
    </script>
@endsection