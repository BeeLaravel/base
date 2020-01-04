@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/gritter/css/jquery.gritter.css')}}"><!-- jquery.gritter 弹窗 -->
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/bootstrap-sweetalert-master/dist/sweetalert.css')}}"><!-- bootstrap-sweetalert 弹窗 -->
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('template/color_admin/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}">
@endsection

@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ url('admin/') }}">首页</a></li>
        <li><a href="{{ url('admin/profile/') }}">我</a></li>
        <li class="active">编辑资料</li>
    </ol>
    <h1 class="page-header">编辑资料 <small>修改我的资料</small></h1>
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
                    <h4 class="panel-title">编辑资料</h4>
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
                        <div class="form-group"><!-- 昵称 -->
                            <label class="control-label col-md-4 col-sm-4" for="name">昵称 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="name" value="{{ $item->name }}" placeholder="昵称" id="name"
                                required />
                            </div>
                        </div>

                        <div class="form-group"><!-- QQ -->
                            <label class="control-label col-md-4 col-sm-4" for="qq">QQ * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="qq" value="{{ $item->profile->qq }}" placeholder="QQ" id="qq" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 微信 -->
                            <label class="control-label col-md-4 col-sm-4" for="wechat">微信 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="wechat" value="{{ $item->profile->wechat }}" placeholder="微信" id="wechat" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 微博 -->
                            <label class="control-label col-md-4 col-sm-4" for="weibo">微博 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="weibo" value="{{ $item->profile->weibo }}" placeholder="微博" id="weibo" />
                            </div>
                        </div>
                        <div class="form-group"><!-- GitHub -->
                            <label class="control-label col-md-4 col-sm-4" for="github">GitHub :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="github" value="{{ $item->profile->github }}" placeholder="GitHub" id="github" />
                            </div>
                        </div>
                        <div class="form-group"><!-- Gitee -->
                            <label class="control-label col-md-4 col-sm-4" for="gitee">Gitee :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="gitee" value="{{ $item->profile->gitee }}" placeholder="Gitee" id="gitee" />
                            </div>
                        </div>

                        <div class="form-group"><!-- 描述 -->
                            <label class="control-label col-md-4 col-sm-4" for="description">描述 :</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea class="form-control" name="description" placeholder="描述" id="description">{{ $item->profile->description }}</textarea>
                            </div>
                        </div>

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

    <script src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>

    <script type="text/javascript">
        $(function(){
            App.init();
            $('form').parsley();
        });
    </script>
@endsection