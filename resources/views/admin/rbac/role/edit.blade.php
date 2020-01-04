@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/parsley/src/parsley.css') }}" />
    <!-- <link href="{{asset('template/color_admin/plugins/jquery-tag-it/css/jquery.tagit.css')}}" rel="stylesheet" /> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/switchery/switchery.min.css') }}">
    <style type="text/css">
        .sub-permissions .permission {
            margin-bottom: 10px;
        }
    </style>
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
                        <div class="form-group"><!-- 标识 -->
                            <label class="control-label col-md-4 col-sm-4" for="slug">标识 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="slug" value="{{ $item['slug'] }}" placeholder="标识" id="slug"
                                required />
                            </div>
                        </div>
                        <div class="form-group"><!-- 标题 -->
                            <label class="control-label col-md-4 col-sm-4" for="title">标题 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="title" value="{{ $item['title'] }}" placeholder="标题" data-parsley-required="true" id="title" />
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
                                <input class="form-control" type="number" name="sort" value="{{ $item['sort'] }}" placeholder="排序" data-parsley-required="true" id="sort" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4">权限 :</label>
                            <div class="col-md-6 col-sm-6">
                                <p>
                                    <a href="javascript: checkAll();" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-check"></i> 全选</a>
                                    <a href="javascript: checkNone();" class="btn btn-sm btn-inverse m-r-5"><i class="fa fa-close"></i> 全不选</a>
                                    <a href="javascript: checkReverse();" class="btn btn-sm btn-inverse m-r-5"><i class="fa fa-magic"></i> 反选</a>
                                </p>
                                <hr>
                                @foreach ( $permissions as $key => $value )
                                    <div class="col-md-3 col-sm-3 permission">
                                        <input type="checkbox" class="parent" name="permissions[]" data-render="switchery" data-theme="purple" value="{{ $value['id'] }}" @if ( in_array($value['id'], $item['permissions']) ) checked @endif /> <span style="font-weight: bolder;">{{ $value['title'] }}</span>
                                    </div>
                                    @if ( $value['children'] )
                                        <div class="col-md-9 col-sm-9 sub-permissions">
                                            @foreach ( $value['children'] as $k => $v )
                                                <div class="col-md-4 col-sm-4 permission">
                                                    <input type="checkbox" name="permissions[]" data-render="switchery" data-theme="purple" value="{{ $v['id'] }}" @if ( in_array($value['id'], $item['permissions']) ) checked @endif /> <span>{{ $v['title'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
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
    <script src="{{ asset('template/color_admin/plugins/parsley/dist/parsley.js') }}"></script>
    <script src="{{ asset('template/color_admin/plugins/parsley/src/i18n/zh_cn.js') }}"></script>
    <!-- <script src="{{ asset('template/color_admin/plugins/jquery-tag-it/js/tag-it.min.js')}} "></script> -->
    <script src="{{ asset('template/color_admin/plugins/switchery/switchery.min.js') }}"></script>

    <script src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>

    <script type="text/javascript">
        $(function(){
            App.init();
            $('form').parsley();

            renderSwitcher();

            $("input[type=checkbox][data-render=switchery].parent").change(function(){
                if ( this.checked ) {
                    $(this).parent().next(".sub-permissions").find(".permission input[type=checkbox]").each(function(){
                        if ( !this.checked ) this.click();
                    });
                } else {
                    $(this).parent().next(".sub-permissions").find(".permission input[type=checkbox]").each(function(){
                        if ( this.checked ) this.click();
                    });
                }
            });
        });
        function renderSwitcher() {
            if ( $('[data-render=switchery]').length !== 0 ) {
                $('[data-render=switchery]').each(function(){
                    var themeColor = '#00acac';

                    if ( $(this).attr('data-theme') ) {
                        switch ( $(this).attr('data-theme') ) {
                            case 'red': themeColor = '#ff5b57'; break;
                            case 'blue': themeColor = '#348fe2'; break;
                            case 'purple': themeColor = '#727cb6'; break;
                            case 'orange': themeColor = '#f59c1a'; break;
                            case 'black': themeColor = '#2d353c'; break;
                        }
                    }

                    var option = {};
                    option.color = themeColor;
                    option.secondaryColor = ($(this).attr('data-secondary-color')) ? $(this).attr('data-secondary-color') : '#dfdfdf';
                    option.className = ($(this).attr('data-classname')) ? $(this).attr('data-classname') : 'switchery';
                    option.disabled = ($(this).attr('data-disabled')) ? true : false;
                    option.disabledOpacity = ($(this).attr('data-disabled-opacity')) ? parseFloat($(this).attr('data-disabled-opacity')) : 0.5;
                    option.speed = ($(this).attr('data-speed')) ? $(this).attr('data-speed') : '0.3s';
                    var switchery = new Switchery(this, option);
                });
            }
        }
        function checkAll() { // 全选
            $("input[type=checkbox]").each(function(){
                if ( !this.checked ) this.click();
            });
        }
        function checkNone() { // 全不选
            $("input[type=checkbox]").each(function(){
                if ( this.checked ) this.click();
            });
        }
        function checkReverse() { // 反选
            $("input[type=checkbox]").each(function(){
                this.click();
            });
        }
    </script>
@endsection
