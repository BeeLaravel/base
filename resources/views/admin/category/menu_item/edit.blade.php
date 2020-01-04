@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/parsley/src/parsley.css') }}" />
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
                        <div class="form-group"><!-- 父级 -->
                            <label class="control-label col-md-4 col-sm-4" for="parent_id">父级 :</label>
                            <div class="col-md-6 col-sm-6">
                                <select name="parent_id" placeholder="父级" class="form-control" id="parent_id">
                                    <option value="0">顶级</option>
                                    @if ( $menu_items )
                                        @foreach ( $menu_items as $id => $title )
                                            <option value="{{ $id }}" @if ( $id==$item['parent_id'] ) selected="selected" @endif>{{ $title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group"><!-- 标题 -->
                            <label class="control-label col-md-4 col-sm-4" for="title">标题 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="title" value="{{ $item['title'] }}" placeholder="标题" id="title"
                                required />
                            </div>
                        </div>
                        <div class="form-group"><!-- 链接 -->
                            <label class="control-label col-md-4 col-sm-4" for="slug">链接 :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="slug" value="{{ $item['slug'] }}" placeholder="链接" id="slug" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 打开方式 -->
                            <label class="control-label col-md-4 col-sm-4" for="open_type">打开方式 :</label>
                            <div class="col-md-6 col-sm-6">
                                <select class="form-control" name="open_type" id="open_type">
                                    <option value="0">当前页面打开</option>
                                    <option value="1" @if ( $item['open_type'] ) selected @endif>新窗口打开</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group"><!-- 图标 -->
                            <label class="control-label col-md-4 col-sm-4" for="icon">图标 :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="icon" value="{{ $item['icon'] }}" placeholder="图标" id="icon" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 描述 -->
                            <label class="control-label col-md-4 col-sm-4" for="description">描述 :</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea class="form-control" name="description" placeholder="描述" id="description">{{ $item['description'] }}</textarea>
                            </div>
                        </div>
                        <div class="form-group"><!-- 排序 -->
                            <label class="control-label col-md-4 col-sm-4" for="sort">排序 :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="number" name="sort" value="{{ $item['sort'] }}" placeholder="排序" id="sort"
                                required min="0" max="255" />
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
