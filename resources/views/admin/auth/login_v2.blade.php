@extends('admin.layouts.page')
@section('styles')
    <link href="{{asset('template/color_admin/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet" />
@endsection
@section('login-cover')
	<div class="login-cover">
	    <div class="login-cover-image"><img src="{{asset('template/color_admin/img/login-bg/bg-1.jpg')}}" data-id="login-cover-image" alt="" /></div>
	    <div class="login-cover-bg"></div>
	</div>
@endsection
@section('page')
    <div class="login login-v2" data-pageload-addclass="animated fadeIn">
        <div class="login-header"><!-- brand -->
            <div class="brand">
                <i class="ion-social-github"></i> BeeSoft Administrator
                <small>矮纸斜行闲作草，晴窗细乳戏分茶。</small>
            </div>
        </div>
        <div class="login-content">
            <form action="index" method="POST" class="margin-bottom-0">
                <div class="form-group m-b-20">
                    <input type="text" class="form-control input-lg" placeholder="账号/邮箱/手机号" />
                </div>
                <div class="form-group m-b-20">
                    <input type="text" class="form-control input-lg" placeholder="密码" />
                </div>
                <div class="checkbox m-b-20">
                    <label>
                        <input type="checkbox" /> 记住密码
                    </label>
                </div>
                <div class="login-buttons">
                    <button type="submit" class="btn btn-success btn-block btn-lg">登录</button>
                </div>
                <div class="m-t-20">
                    没有注册？ <a href="register_v3" class="text-success">这里</a> 注册
                </div>
            </form>
        </div>
    </div>
    <ul class="login-bg-list">
        <li class="active"><a href="#" data-click="change-bg"><img src="{{asset('template/color_admin/img/login-bg/bg-1.jpg')}}" alt="" /></a></li>
        <li><a href="#" data-click="change-bg"><img src="{{asset('template/color_admin/img/login-bg/bg-2.jpg')}}" alt="" /></a></li>
        <li><a href="#" data-click="change-bg"><img src="{{asset('template/color_admin/img/login-bg/bg-3.jpg')}}" alt="" /></a></li>
        <li><a href="#" data-click="change-bg"><img src="{{asset('template/color_admin/img/login-bg/bg-4.jpg')}}" alt="" /></a></li>
        <li><a href="#" data-click="change-bg"><img src="{{asset('template/color_admin/img/login-bg/bg-5.jpg')}}" alt="" /></a></li>
        <li><a href="#" data-click="change-bg"><img src="{{asset('template/color_admin/img/login-bg/bg-6.jpg')}}" alt="" /></a></li>
    </ul>
@endsection
@section('scripts')
	<script src="{{asset('template/color_admin/js/login-v2.demo.min.js')}}"></script>
	<script src="{{asset('template/color_admin/js/apps.min.js')}}"></script>

	<script>
		$(document).ready(function() {
			App.init();
			LoginV2.init();
		});
	</script>
@endsection