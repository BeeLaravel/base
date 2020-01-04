@extends('admin.layouts.page')
@section('styles')
    <link href="{{asset('template/color_admin/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet" />
    <link href="{{asset('template/color_admin/plugins/parsley/src/parsley.css')}}" rel="stylesheet" />
@endsection
@section('page')
	<div class="login login-with-news-feed"><!-- login -->
        <div class="news-feed"><!-- news-feed -->
            <div class="news-image"><!-- news-image -->
                <img src="{{asset('template/color_admin/img/login-bg/bg-7.jpg')}}" data-id="login-cover-image" alt="" />
            </div>
            <div class="news-caption"><!-- news-caption -->
                <h4 class="caption-title"><i class="fa fa-diamond text-success"></i> {{ config('beesoft.domain') }}</h4>
                <p>利欲驱人万火牛，江湖浪迹一沙鸥。日长似岁闲方觉，事大如天醉亦休。<br>砧杵敲残深巷月，井梧摇落故园秋。欲舒老眼无高处，安得元龙百尺楼。</p>
            </div>
        </div>
        <div class="right-content"><!-- right-content -->
            <div class="login-header"><!-- login-header -->
                <div class="brand" style="padding-right: 0; width: 380px;">
                    <i class="ion-social-github fa-2x text-inverse"></i> <span style="font-size: 50px; font-weight: bolder;">{{ config('beesoft.domain') }}</span>
                    <small>矮纸斜行闲作草，晴窗细乳戏分茶。</small>
                </div>
            </div>
            <div class="login-content"><!-- login-content -->
                <form action="{{ url('/admin/login') }}" method="POST" class="margin-bottom-0">
                    @csrf
                    <div class="form-group m-b-15">
                        <input type="text" name="email" value="{{ old('email') }}" class="form-control input-lg{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Account / EMail Address / Phone Number') }}" id="email" data-parsley-required="true" autofocus required />
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form-group m-b-15">
                        <input type="password" name="password" class="form-control input-lg{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" id="password" data-parsley-required="true" required />
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="checkbox m-b-30">
                        <label>
                            <input type="checkbox" name="remember" data-parsley-excluded="true" {{ old('remember') ? 'checked' : '' }} /> {{ __('Remember Me') }}
                        </label>

                        <a href="{{ url('/admin/password/reset') }}" class="text-success">忘记密码</a>
                    </div>
                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg">{{ __('Login') }}</button>
                    </div>
                    <div class="m-t-20 m-b-40 p-b-40">
                        {!! __('Not a member yet? Click :here to register.', [
                            'here' => '<a href="'.url('admin/register').'" class="text-success">'.__('here').'</a>',
                        ]) !!}
                    </div>
                    <hr />
                    <p class="text-center text-inverse">
                        &copy; {{ config('beesoft.domain') }} All Right Reserved {{ config('beesoft.years') }}
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('template/color_admin/plugins/parsley/dist/parsley.min.js') }}"></script>
    <script src="{{ asset('template/color_admin/plugins/parsley/src/i18n/zh_cn.js') }}"></script>
	<script src="{{ asset('template/color_admin/js/apps.min.js') }}"></script>

	<script>
		$(document).ready(function() {
			App.init();
            $('form').parsley();
		});
	</script>
@endsection