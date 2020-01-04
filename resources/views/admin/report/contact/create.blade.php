@extends('admin.layouts.admin')

@section('admin-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('asset_admin/assets/plugins/parsley/src/parsley.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" />
@endsection

@section('admin-content')
    <div id="content" class="content">
        <ol class="breadcrumb pull-right">
            <li><a href="{{url('/admin/')}}">首页</a></li>
            <li><a href="{{url('/admin/bid')}}">竞价</a></li>
            <li><a href="{{url('/admin/report')}}">报表</a></li>
            <li class="active">提交</li>
        </ol>
        <h1 class="page-header">竞价报表 <small>今日报表</small></h1>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-inverse" data-sortable-id="form-validation-1">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                        <h4 class="panel-title">竞价报表提交</h4>
                    </div>
                    @if ( $errors->any() )
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ( $errors->all() as $error )
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body panel-form">
                        <form class="form-horizontal form-bordered" data-parsley-validate="true" action="{{ url('admin/report') }}" method="POST" enctype="multipart/form-data" id="create">
                            {{ csrf_field() }}
                            @if ( !auth('admin')->user()->hospital_id )
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4" for="hospital_id">医院 * :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <select class="form-control" name="hospital_id" data-parsley-required data-parsley-required-message="请选择医院" id="hospital_id">
                                            <option value="">==请选择==</option>
                                            @foreach ( $hospitals as $item )
                                                <option value="{{$item['id']}}">{{$item['description']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4">医院 :</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input class="form-control" type="text" value="{{auth('admin')->user()->hospital->description}}" readonly="readonly" />
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="date">日期 :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="date" value="{{old('date')?:date('Y/m/d', time()-86400)}}" placeholder="" data-parsley-required="true" data-parsley-required-message="请选择日期" id="date" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="consumption">消费 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="consumption" value="{{ old('consumption')?:0 }}" placeholder="消费" data-parsley-whitespace="trim" data-parsley-required="true" data-parsley-required-message="请输入消费" data-parsley-type="number" data-parsley-type-message="“消费”必须是数字，请检查前后是否有空格" data-parsley-min="0" data-parsley-min-message="消费必须大于 0" id="consumption" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="real_consumption">实际消费 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="text" name="real_consumption" value="{{ old('real_consumption')?:0 }}" placeholder="实际消费" data-parsley-whitespace="trim" data-parsley-required="true" data-parsley-required-message="请输入实际消费" data-parsley-type="number" data-parsley-type-message="“实际消费”必须是数字，请检查前后是否有空格" data-parsley-min="0" data-parsley-min-message="实际消费必须大于 0" id="real_consumption" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="useful_dialog">有效对话 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="number" name="useful_dialog" value="{{ old('useful_dialog')?:0 }}" placeholder="有效对话" data-parsley-whitespace="trim" data-parsley-required="true" data-parsley-required-message="请输入有效对话" data-parsley-type="integer" data-parsley-type-message="“有效对话”必须是数字，请检查前后是否有空格" data-parsley-min="0" data-parsley-min-message="有效对话必须大于 0" id="useful_dialog" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="useless_dialog">无效对话 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="number" name="useless_dialog" value="{{ old('useless_dialog')?:0 }}" placeholder="无效对话" data-parsley-whitespace="trim" data-parsley-required="true" data-parsley-required-message="请输入无效对话" data-parsley-type="integer" data-parsley-type-message="“无效对话”必须是数字，请检查前后是否有空格" data-parsley-min="0" data-parsley-min-message="无效对话必须大于 0" id="useless_dialog" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="bespeak">预约数 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="number" name="bespeak" value="{{ old('bespeak')?:0 }}" placeholder="预约数" data-parsley-whitespace="trim" data-parsley-required="true" data-parsley-required-message="请输入预约数" data-parsley-type="integer" data-parsley-type-message="“预约数”必须是数字，请检查前后是否有空格" data-parsley-min="0" data-parsley-min-message="预约数必须大于 0" id="bespeak" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="visit">到诊数 * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <input class="form-control" type="number" name="visit" value="{{ old('visit')?:0 }}" placeholder="到诊数" data-parsley-whitespace="trim" data-parsley-required="true" data-parsley-required-message="请输入到诊数" data-parsley-type="integer" data-parsley-type-message="“到诊数”必须是数字，请检查前后是否有空格" data-parsley-min="0" data-parsley-min-message="到诊数必须大于 0" id="visit" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4" for="description">描述 :</label>
                                <div class="col-md-6 col-sm-6">
                                    <textarea class="form-control" name="description" placeholder="描述" id="description">{{old('description')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4"></label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="hidden" name="id" value="" id="id" />
                                    <button type="submit" class="btn btn-primary">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('admin-js')
    <script type="text/javascript" src="{{ asset('asset_admin/assets/plugins/parsley/dist/parsley.js') }}"></script>
    <script type="text/javascript" src="{{asset('assets/vendor/bootstrap/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendor/bootstrap/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js')}}"></script>
    <script src="{{asset('assets/vendor/layer/layer.js')}}"></script>

    <script type="text/javascript">
        $(function(){
            ajax_data();

            $("form#create").on("submit", function(){
                $("button[type=submit]").attr("disabled", "disabled");
                return true;
            });
        });
        $("input[name=date]").datepicker({
            format: 'yyyy/mm/dd',
            autoclose: true,
            calendarWeeks: true,
            // immediateUpdates: true,
            todayBtn: "linked",
            todayHighlight: true,
            // startDate: "-6d",
            // endDate: "0d",
            language: 'zh-CN'
        }).on("changeDate", function(e) {
            ajax_data();
        });
        function ajax_data(date) {
            layer.load();

            $.ajax({
                method: 'get',
                url: "{{url('/admin/report/0')}}",
                dataType: 'json',
                data: {
                    'date': $('#date').val()
                },
                success: function(data){
                    if ( data ) {
                        $("#id").val(data.id);
                        $("#consumption").val(data.consumption);
                        $("#real_consumption").val(data.real_consumption);
                        $("#useful_dialog").val(data.useful_dialog);
                        $("#useless_dialog").val(data.useless_dialog);
                        $("#bespeak").val(data.bespeak);
                        $("#visit").val(data.visit);
                        $("#description").val(data.description);

                        layer.closeAll('loading');
                        layer.msg('报表已提交，编辑修改！');
                    } else {
                        report_error();
                    }
                },
                error: function(data){
                    report_error();
                }
            });
        }
        function report_error() {
            $("#id").val('');
            $("#consumption").val(0);
            $("#real_consumption").val(0);
            $("#useful_dialog").val(0);
            $("#useless_dialog").val(0);
            $("#bespeak").val(0);
            $("#visit").val(0);
            $("#description").val('');

            layer.closeAll('loading');
            layer.msg('报表未提交，编辑提交！');
        }
    </script>
@endsection