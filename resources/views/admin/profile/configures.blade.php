@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/gritter/css/jquery.gritter.css')}}"><!-- jquery.gritter 弹窗 -->
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/bootstrap-sweetalert-master/dist/sweetalert.css')}}"><!-- bootstrap-sweetalert 弹窗 -->
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/jquery-tag-it/css/jquery.tagit.css')}}" />
@endsection

@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/') }}">首页</a></li>
        <li><a href="{{ url('admin/profile/') }}">我</a></li>
        <li class="active">我的配置</li>
    </ol>
    <h1 class="page-header">我的配置 <small>我的分类|我的标签</small></h1>
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
                    <h4 class="panel-title">个人配置</h4>
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
                    <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PATCH') }}
                        @foreach ( $configures as $key => $value )
                            <div class="form-group"><!-- {{$value}} -->
                                <label class="control-label col-md-4 col-sm-4" for="{{$key}}">{{$value}} * :</label>
                                <div class="col-md-6 col-sm-6">
                                    <ul id="{{$key}}" class="form-control success">
                                        @if ( $item->profile->{$key} )
                                            @foreach ( json_decode($item->profile->{$key}, true) as $i )
                                                <li>{{ $i }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4"></label>
                            <div class="col-md-6 col-sm-6">
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('template/color_admin/plugins/parsley/dist/parsley.min.js') }}"></script>
    <script src="{{ asset('template/color_admin/plugins/parsley/src/i18n/zh_cn.js') }}"></script>
    <script src="{{ asset('template/color_admin/plugins/jquery-tag-it/js/tag-it.min.js')}} "></script>

    <script src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>

    <script type="text/javascript">
        $(function(){
            App.init();
            $('form').parsley();

            @foreach ( $configures as $key => $value )
                $("#{{$key}}").tagit({
                    fieldName: "{{$key}}[]",
                    // availableTags: ["commons"],
                    // tagLimit: 10,
                    placeholderText: '{{$value}}',
                    removeConfirmation: true,
                    allowSpaces: true
                });
            @endforeach
        });
    </script>
@endsection