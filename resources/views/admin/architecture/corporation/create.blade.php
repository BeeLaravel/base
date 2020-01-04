@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/parsley/src/parsley.css') }}" />
    <!-- <link href="{{asset('template/color_admin/plugins/jquery-tag-it/css/jquery.tagit.css')}}" rel="stylesheet" /> -->
@endsection

@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{url('/admin')}}">首页</a></li>
        <li><a href="{{url($parent_link)}}">{{$parent_title}}</a></li>
        <li><a href="{{url($link)}}">{{$title}}</a></li>
        <li class="active">新增</li>
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
                    <h4 class="panel-title">新增</h4>
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
                    <form class="form-horizontal form-bordered" action="{{ url($link) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group"><!-- 标识 -->
                            <label class="control-label col-md-4 col-sm-4" for="slug">标识 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="slug" value="{{ old('slug') }}" placeholder="标识" id="slug"
                                required />
                            </div>
                        </div>
                        <div class="form-group"><!-- 标题 -->
                            <label class="control-label col-md-4 col-sm-4" for="title">标题 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="标题" data-parsley-required="true" id="title" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 描述 -->
                            <label class="control-label col-md-4 col-sm-4" for="description">描述 :</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea class="form-control" name="description" placeholder="描述" id="description">{{old('description')}}</textarea>
                            </div>
                        </div>
                        <div class="form-group"><!-- 地址 -->
                            <label class="control-label col-md-4 col-sm-4" for="address">地址 :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="address" value="{{ old('address') }}" placeholder="地址" id="address" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 电话 -->
                            <label class="control-label col-md-4 col-sm-4" for="tel">电话 :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="tel" value="{{ old('tel') }}" placeholder="电话" id="tel" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 邮编 -->
                            <label class="control-label col-md-4 col-sm-4" for="postcode">邮编 :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="text" name="postcode" value="{{ old('postcode') }}" placeholder="邮编" id="postcode" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 父级 -->
                            <label class="control-label col-md-4 col-sm-4" for="parent_id">父级 :</label>
                            <div class="col-md-6 col-sm-6">
                                <select name="parent_id" placeholder="父级" class="form-control" id="parent_id">
                                    <option value="0">顶级</option>
                                    @if ( $parents )
                                        @foreach ( $parents as $id => $title )
                                            <option value="{{ $id }}" @if ( old('parent_id')==$id ) selected="selected" @endif>{{ $title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group"><!-- 排序 -->
                            <label class="control-label col-md-4 col-sm-4" for="sort">排序 * :</label>
                            <div class="col-md-6 col-sm-6">
                                <input class="form-control" type="number" name="sort" value="{{ old('sort')??config('beesoft.sort_default') }}" placeholder="排序" data-parsley-required="true" id="sort" />
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

    <script src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>

    <script type="text/javascript">
        $(function(){
            App.init();
            $('form').parsley();
        });
    </script>
@endsection
