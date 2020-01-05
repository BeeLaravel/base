<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('title', '首页') - {{ config('beesoft.blog.title') }}</title>
	<meta name="keywords" content="{{ config('beesoft.blog.keywords') }}">
	<meta name="description" content="{{ config('beesoft.blog.description') }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
	<div id="app" class="{{ route_class() }}-page">
		@include('blog.layouts._header')
		<div class="container">
			@include('blog.shared._messages')
			@yield('content')
		</div>
		@include('blog.layouts._footer')
	</div>

	<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>