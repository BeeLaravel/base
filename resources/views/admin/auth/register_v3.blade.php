@extends('admin.layouts.page')
@section('styles')
    <link href="{{asset('template/color_admin/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet" />
    <link href="{{asset('template/color_admin/plugins/parsley/src/parsley.css')}}" rel="stylesheet" />
@endsection
@section('page')
    <div class="register register-with-news-feed"><!-- register -->
        <div class="news-feed"><!-- news-feed -->
            <div class="news-image">
                <img src="{{asset('template/color_admin/img/login-bg/bg-8.jpg')}}" alt="" />
            </div>
            <div class="news-caption">
                <h4 class="caption-title"><i class="fa fa-github text-success"></i> {{ config('beesoft.domain') }}</h4>
                <p>本是瑶台第一枝，谪来尘世具芳姿。如何不遇林和靖？飘泊天涯更水涯。<br>冰姿不怕雪霜侵，羞傍琼楼傍古岑。标格原因独立好，肯教福贵负初心。</p>
            </div>
        </div>
        <div class="right-content"><!-- right-content -->
            <h1 class="register-header"><!-- register-header -->
                {{ __('Register') }} {{ config('beesoft.domain') }}
                <small>白云斜挂蔚蓝天，独自登临一怅然。<br>欲望家乡何处似？乱峰深里翠如烟。</small>
            </h1>
            <div class="register-content"><!-- register-content -->
                <form action="{{ url('/admin/register') }}" method="POST" class="margin-bottom-0">
                    @csrf
                    <label class="control-label">{{ __('Name') }}</label>
                    <div class="row m-b-15">
                        <div class="col-md-12">
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" placeholder="{{ __('Name') }}" required autofocus />
                        </div>
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <label class="control-label">{{ __('E-Mail Address') }}</label>
                    <div class="row m-b-15">
                        <div class="col-md-12">
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" placeholder="{{ __('E-Mail Address') }}" required />
                        </div>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <label class="control-label">{{ __('Password') }}</label>
                    <div class="row m-b-15">
                        <div class="col-md-12">
                            <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" placeholder="{{ __('Password') }}" required />
                        </div>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <label class="control-label">{{ __('Confirm Password') }}</label>
                    <div class="row m-b-15">
                        <div class="col-md-12">
                            <input type="password" name="password_confirmation" class="form-control" id="password-confirm" placeholder="{{ __('Confirm Password') }}" required />
                        </div>
                    </div>

                    <div class="checkbox m-b-30">
                        <label>
                            <input type="checkbox" name="agree_protocal" required /> {{ __('Read and accept ":UserProtocal" and ":PrivacyProtectionStatement".', [
                                'UserProtocal' => config('beesoft.domain').' '.__('User Protocal'),
                                'PrivacyProtectionStatement' => config('beesoft.domain').' '.__('Privacy Protection Statement'),
                            ]) }}
                        </label>
                    </div>
                    <div class="register-buttons">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Register') }}</button>
                    </div>
                    <div class="m-t-20 m-b-40 p-b-40">
                        {!! __('Already a member? Click :here to login.', [
                            'here' => '<a href="'.url('admin/login').'" class="text-success">'.__('here').'</a>',
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
    <script src="{{asset('template/color_admin/plugins/parsley/dist/parsley.min.js')}}"></script>
    <script src="{{asset('template/color_admin/plugins/parsley/src/i18n/zh_cn.js')}}"></script>
	<script src="{{asset('template/color_admin/js/apps.min.js')}}"></script>

	<script>
		$(document).ready(function() {
			App.init();
            $('form').parsley();
		});
	</script>
@endsection