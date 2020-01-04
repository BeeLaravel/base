@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/parsley/src/parsley.css') }}" />
    <link href="{{asset('template/color_admin/plugins/jquery-tag-it/css/jquery.tagit.css')}}" rel="stylesheet" />
    <!-- <link href="{{asset('template/color_admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" /> -->
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
                @if ( count($errors)>0 )
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
                        <input type="hidden" name="id" value="{{$item['id']}}">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="title">标题 * :</label>
                            <div class="col-md-8 col-sm-8">
                                <input class="form-control" type="text" name="title" value="{{ $item['title'] }}" placeholder="标题" data-parsley-required="true" id="title" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 标识 -->
                            <label class="control-label col-md-3 col-sm-3" for="slug">标识 * :</label>
                            <div class="col-md-8 col-sm-8">
                                <input class="form-control" type="text" name="slug" value="{{ $item['slug'] }}" placeholder="标识" data-parsley-required="true" id="slug" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 内容 -->
                            <label class="control-label col-md-3 col-sm-3" for="url">内容 * :</label>
                            <div class="col-md-8 col-sm-8">
                                <textarea name="content" class="form-control" data-parsley-required="true">{{ $item['content'] }}</textarea>
                            </div>
                        </div>
                        <div class="form-group"><!-- 描述 -->
                            <label class="control-label col-md-3 col-sm-3" for="description">描述 :</label>
                            <div class="col-md-8 col-sm-8">
                                <textarea class="form-control" name="description" placeholder="描述" id="description">{{ $item['description'] }}</textarea>
                            </div>
                        </div>
                        <div class="form-group"><!-- 排序 -->
                            <label class="control-label col-md-3 col-sm-3" for="sort">排序 :</label>
                            <div class="col-md-8 col-sm-8">
                                <input class="form-control" type="number" name="sort" value="{{ $item['sort']??config('beesoft.sort_default') }}" placeholder="排序" data-parsley-required="true" id="sort" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3"></label>
                            <div class="col-md-8 col-sm-8">
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
    <!-- <script src="{{ asset('template/color_admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}} "></script> -->
    <!-- <script src="{{ asset('template/color_admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js')}} "></script> -->

    <script src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>

    <script type="text/javascript">
        $(function(){
            App.init();
            $('form').parsley();
        });
    </script>
@endsection
