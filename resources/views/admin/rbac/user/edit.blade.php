@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/parsley/src/parsley.css') }}" />
    <!-- <link href="{{asset('template/color_admin/plugins/jquery-tag-it/css/jquery.tagit.css')}}" rel="stylesheet" /> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/select2/dist/css/select2.min.css') }}" />
@endsection

@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{url('/admin')}}">首页</a></li>
        <li><a href="{{url($parent_link)}}">{{$parent_title}}</a></li>
        <li><a href="{{url($link)}}">{{$title}}</a></li>
        <li class="active">编辑</li>
    </ol>
    <h1 class="page-header">{{$title}} <small>{{$description}}</small></h1>
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
                    <h4 class="panel-title">编辑</h4>
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
                    <form class="form-horizontal form-bordered" action="{{ url($link.'/'.$item['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PATCH') }}
                        <div class="form-group"><!-- 电话 -->
                            <label class="control-label col-md-4 col-sm-4" for="phone">电话 :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="number" name="phone" value="{{ $item['phone'] }}" placeholder="电话" data-parsley-required="true" id="phone" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 邮箱 -->
                            <label class="control-label col-md-4 col-sm-4" for="email">邮箱 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="email" value="{{ $item['email'] }}" placeholder="邮箱" id="email"
                                required />
                            </div>
                        </div>
                        <div class="form-group"><!-- 姓名 -->
                            <label class="control-label col-md-4 col-sm-4" for="name">姓名 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="name" value="{{ $item['name'] }}" placeholder="姓名" data-parsley-required="true" id="name" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 描述 -->
                            <label class="control-label col-md-4 col-sm-4" for="description">描述 :</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea class="form-control" name="description" placeholder="描述" id="description">{{ $item['description'] }}</textarea>
                            </div>
                        </div>
                        <div class="form-group"><!-- 密码 -->
                            <label class="control-label col-md-4 col-sm-4" for="password">密码 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="password" name="password" value="{{ old('password') }}" placeholder="密码" id="password" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 公司 -->
                            <label class="control-label col-md-4 col-sm-4" for="corporation_id">公司 :</label>
                            <div class="col-md-6 col-sm-6">
                                <select name="corporation_id" placeholder="公司" class="form-control" id="corporation_id">
                                    <option value="0">未知</option>
                                    @if ( $corporations )
                                        @foreach ( $corporations as $id => $title )
                                            <option value="{{ $id }}" @if ( $id==$item['corporation_id'] ) selected="selected" @endif>{{ $title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group"><!-- 站点 -->
                            <label class="control-label col-md-4 col-sm-4" for="sites">站点 :</label>
                            <div class="col-md-6 col-sm-6">
                                <select class="form-control select2" name="sites[]" id="sites" multiple>
                                    @foreach ( $sites as $site )
                                        <option value="{{ $site->id }}" @if ( in_array($site->id, $item['sites']) ) selected @endif>{{ $site->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group"><!-- 部门 -->
                            <label class="control-label col-md-4 col-sm-4" for="departments">部门 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <select class="form-control select2" name="departments[]" id="departments" data-parsley-required="true" multiple>
                                    @foreach ( $departments as $department )
                                        <option value="{{ $department->id }}" @if ( in_array($department->id, $item['departments']) ) selected @endif>{{ $department->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group"><!-- 角色 -->
                            <label class="control-label col-md-4 col-sm-4" for="roles">角色 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <select class="form-control select2" name="roles[]" id="roles" data-parsley-required="true" multiple>
                                    @foreach ( $roles as $role )
                                        <option value="{{ $role->id }}" @if ( in_array($role->id, $item['roles']) ) selected @endif>{{ $role->title }}</option>
                                    @endforeach
                                </select>
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
    <!-- <script src="{{ asset('template/color_admin/plugins/jquery-tag-it/js/tag-it.min.js')}} "></script> -->
    <script src="{{ asset('template/color_admin/plugins/select2/dist/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>

    <script type="text/javascript">
        $(function(){
            App.init();
            $('form').parsley();
            $(".select2").select2({
                placeholder: "请选择角色"
            });
        });
    </script>
@endsection
