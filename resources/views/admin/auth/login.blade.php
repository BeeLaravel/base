@extends('admin.layouts.page')
@section('styles')
    <link href="{{asset('template/color_admin/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet" />
@endsection
@section('page')
    <div class="login bg-black animated fadeInDown"><!-- login -->
        <div class="login-header"><!-- brand -->
            <div class="brand">
                <i class="ion-social-github text-inverse"></i> BeeSoft Administrator
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
            </form>
        </div>
    </div>
@endsection
@section('scripts')
	<script src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>
	
	<script>
		$(document).ready(function() {
			App.init();
		});
	</script>
@endsection