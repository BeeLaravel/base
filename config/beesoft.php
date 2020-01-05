<?php
return [
	'blog' => [
		'link' => 'http://www.beesoft.ink',
		'slug' => 'BeeSoft',
		'title' => 'BeeSoft.ink', 
		'keywords' => 'behero,beesoft,blog,php,laravel,golang,python,javascript',
		'description' => 'Behero\'s Blog - BeeSoft.ink - PHP|Laravel|GoLang|Python|JavaScript',
		'email' => 'beherochuling@163.com',
	],
    'years' => env('BEESOFT_YEARS', '2009-2018'),
    'sort_default' => env('SORT_DEFAULT', '255'),

    'websocket' => [
		'port' => env('WEBSOCKET_PORT', 9501),
	],
	'websockets' => [
		'port' => env('WEBSOCKETS_PORT', 9502),
	],

	'links' => [
		'default_type' => env('LINKS_DEFAULT_TYPE', 'commons'), // commons links
	],
	'pictures' => [
		'default_type' => env('PICTURES_DEFAULT_TYPE', 'commons'), // commons pictures
	],

	'menu' => [ // 菜单
		'new' => 10, // NEW 标记 天数
	],
	'page' => [
		'size' => 50,
	],
	'words' => [ // 词汇
		'unset' => '(unset)',
		'unknow' => '(unknow)',
	],
];
