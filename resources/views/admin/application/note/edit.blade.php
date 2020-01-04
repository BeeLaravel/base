@extends('admin.layouts.base')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/parsley/src/parsley.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/select2/dist/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('template/color_admin/plugins/jquery-tag-it/css/jquery.tagit.css') }}" />

    <style type="text/css">
        .editor-help>.tab-content {
            padding: 0;
            margin-bottom: 0;
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
                    <form class="form-horizontal form-bordered" action="{{ url($link.'/'.$item['id']) }}" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        {{ method_field('PATCH') }}
                        <input type="hidden" name="id" value="{{$item['id']}}">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3" for="title">标题 * :</label>
                            <div class="col-md-8 col-sm-8">
                                <input class="form-control" type="text" name="title" value="{{ $item['title'] }}" placeholder="标题" data-parsley-required="true" id="title" />
                            </div>
                        </div>
                        <div class="form-group"><!-- 内容 -->
                            <label class="control-label col-md-3 col-sm-3" for="myEditor">内容 * :</label>
                            <div class="col-md-8 col-sm-8">
                                <div id="editor" class="editor">
                                    @if ( $item['content_type']=='ueditor' )
                                        <script type="text/plain" name="content" id="myEditor">{!! $item['content'] !!}</script>
                                    @else
                                        <textarea name="content" class="form-control" id='myEditor'>{{ $item['content'] }}</textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group"><!-- 分类 -->
                            <label class="control-label col-md-3 col-sm-3" for="category_id">分类 * :</label>
                            <div class="col-md-8 col-sm-8">
                                <select name="category_id" value="{{ $item['category_id'] }}" placeholder="分类" class="form-control select2" id="category_id">
                                    <option value="0">未分类</option>
                                    @if ( $categories )
                                        @foreach ( $categories as $id => $category )
                                            <option value="{{ $id }}" @if ( $item['category_id']==$id ) selected="selected" @endif>{{ $category }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group"><!-- 标签 -->
                            <label class="control-label col-md-3 col-sm-3" for="tags">标签 :</label>
                            <div class="col-md-8 col-sm-8">
                                <ul id="tags" class="success">
                                    @if ( $item->tags )
                                        @foreach ( $item->tags as $tag )
                                            <li>{{ $tag->title }}</li>
                                        @endforeach
                                    @endif
                                </ul>
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
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/parsley/dist/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/parsley/src/i18n/zh_cn.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/color_admin/plugins/jquery-tag-it/js/tag-it.min.js')}} "></script>

    <script type="text/javascript" src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>
    <script type="text/javascript">
        $(function(){
            App.init();

            $('form').parsley();

            $(".select2").select2({
                allowClear: true
            });

            $("#tags").tagit({
                fieldName: "tags[]",
                availableTags: {!! $tags??'' !!},
                tagLimit: 5, // 最大标签数
                placeholderText: '标签',
                allowSpaces: true
            });
        });
    </script>
    @switch ( $item['content_type'] )
        @case('ueditor')
            @include('vendor.ueditor.assets')
            <script type="text/javascript">
                var ue = UE.getEditor('myEditor');
                ue.ready(function() {
                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
                });
            </script>
        @break
        @case('tinymce')
        @case('html')
            @include('vendor.tinymce.tpl')
        @break
        @case('endaEdit')
            @include('vendor.editor.head')
        @break
        @case('editormd')
        @case('markdown')
        @default
            @include('vendor.markdown.encode', [
                'editors' => ['editor']
            ])
    @endswitch
@endsection
