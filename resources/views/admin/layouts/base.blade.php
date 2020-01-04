@extends('admin.layouts.page')
@section('page')
    <div id="header" class="header navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="{{url('template/color/index')}}" class="navbar-brand"><!-- logo -->
                    <i class="fa fa-github fa-2x"></i>
                    <span style="font-size: 30px; font-weight: bold;">BeeSoft</span>
                </a>
                <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <form class="navbar-form full-width"><!-- 搜索 -->
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="输入关键词" />
                            <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </li>
                <li class="dropdown"><!-- 通知 -->
                    <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                        <i class="fa fa-bell-o"></i>
                        <span class="label">5</span>
                    </a>
                    <ul class="dropdown-menu media-list pull-right animated fadeInDown">
                        <li class="dropdown-header">通知 (5)</li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-bug media-object bg-red"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading">Server Error Reports</h6>
                                    <div class="text-muted f-s-11">3 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><img src="{{asset('template/color_admin/img/user-1.jpg')}}" class="media-object" alt="" /></div>
                                <div class="media-body">
                                    <h6 class="media-heading">John Smith</h6>
                                    <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                    <div class="text-muted f-s-11">25 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><img src="{{asset('template/color_admin/img/user-2.jpg')}}" class="media-object" alt="" /></div>
                                <div class="media-body">
                                    <h6 class="media-heading">Olivia</h6>
                                    <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                    <div class="text-muted f-s-11">35 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-plus media-object bg-green"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading"> New User Registered</h6>
                                    <div class="text-muted f-s-11">1 hour ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-envelope media-object bg-blue"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading"> New Email From John</h6>
                                    <div class="text-muted f-s-11">2 hour ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer text-center">
                            <a href="javascript:;">View more</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown navbar-user"><!-- 用户 -->
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('/storage/'.auth('admin')->user()->avatar)}}" alt="" /> {{auth('admin')->user()->name}}
                        <span class="hidden-xs"></span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu animated fadeInLeft">
                        <li class="arrow"></li>
                        <li><a href="{{ url('/admin/profile') }}">编辑资料</a></li>
                        <li><a href="{{ url('/admin/profile/configures') }}">个人配置</a></li>
                        <li><a href="{{ url('/admin/profile/avatar') }}">修改头修</a></li>
                        <li><a href="{{ url('/admin/profile/password') }}">修改密码</a></li>
                        <li><a href="{{ url('/admin/profile/email') }}"><span class="badge badge-danger pull-right">2</span> 邮箱</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript: void(0);" onclick="javascript: exit()">退出</a></li>
                        <form action="{{ url('/admin/logout') }}" method="POST" id="exit_form">
                            @csrf
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div id="sidebar" class="sidebar"><!-- 侧边栏 -->
        <div data-scrollbar="true" data-height="100%"><!-- 侧边栏 scrollbar -->
            <ul class="nav"><!-- 侧边栏 用户 -->
                <li class="nav-profile">
                    <div class="image">
                        <a href="javascript:;"><img src="{{asset('/storage/'.auth('admin')->user()->avatar)}}" alt="" /></a>
                    </div>
                    <div class="info">
                        {{auth('admin')->user()->name}}
                        <small style="font-size: 80%;">{{auth('admin')->user()->email}}</small>
                    </div>
                </li>
            </ul>
            @include('admin.layouts.menu')
        </div>
    </div>
    <div class="sidebar-bg"></div><!-- 侧边栏 背景 -->
    <div id="content" class="content @if ( $config['content-full-width']??0 ) content-full-width @endif">@yield('content')</div><!-- content -->
    @include('admin.layouts.theme')
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a><!-- scroll-to-top -->

    <script type="text/javascript" src="/statics/admin/scripts/app.js"></script>
    <script type="text/javascript">
        function exit(){ // 退出登录
            $("#exit_form").submit();
        }
    </script>
@endsection
