<?php
// ## Admin
Auth::routes(); // Route:auth();

Route::group([
	'middleware' => ['auth:admin']
], function ($router) {
	// ### 个人
	Route::group([
		'prefix' => 'profile',
	], function ($router) {
		$router->get('/', 'Profile\IndexController@profile'); // 编辑资料
		$router->get('configures', 'Profile\IndexController@configures'); // 个人配置
		$router->get('avatar', 'Profile\IndexController@avatar'); // 修改头像
		$router->get('password', 'Profile\IndexController@password'); // 修改密码

		$router->patch('/', 'Profile\IndexController@updateProfile'); // 编辑资料
		$router->patch('configures', 'Profile\IndexController@updateConfigures'); // 个人配置
		$router->patch('avatar', 'Profile\IndexController@updateAvatar'); // 修改头像
		$router->patch('password', 'Profile\IndexController@updatePassword'); // 修改密码
	});

	// $router->resource('/', 'User\CategoryController'); // 首页 分类

	// ### RBAC
	$router->resource('permissions', 'RBAC\PermissionController'); // 结点
	$router->resource('roles', 'RBAC\RoleController'); // 角色
	$router->resource('users', 'RBAC\UserController'); // 用户
	// ### 架构
	// $router->get('corporations/export', 'Architecture\CorporationController@export'); // 导出
	// $router->get('corporations/download/{type}', 'Architecture\CorporationController@download'); // 下载
	$router->resource('corporations', 'Architecture\CorporationController'); // 公司
	$router->resource('sites', 'Architecture\SiteController'); // 站点
	$router->resource('departments', 'Architecture\DepartmentController'); // 部门
	// ### 其他
	// ### 数据
	// ### 流程
	$router->resource('workflows', 'Workflow\WorkflowController'); // 工作流
	$router->resource('places', 'Workflow\PlaceController'); // 状态
	$router->resource('transitions', 'Workflow\TransitionController'); // 过渡
	// ### 配置
	$router->resource('configures', 'Configure\ConfigureController', ['except' => ['show']]); // 配置
	$router->get('configures/{configure_id}', 'Configure\ConfigureItemController@index'); // 配置项 列表
	$router->resource('configure-items', 'Configure\ConfigureItemController'); // 配置项
	$router->resource('configure-templates', 'Configure\ConfigureTemplateController'); // 配置模板

	// ### 微信

	// ### 费用
	// ### Report 报表
	$router->resource('report_sems', 'Report\SemController'); // 竞价
	$router->resource('report_platforms', 'Report\PlatformController'); // 电商
	$router->resource('report_contacts', 'Report\ContactController'); // 网电
	$router->resource('report_introduces', 'Report\IntroduceController'); // 老带新

	// ### Category 分类
	$router->resource('menus', 'Category\MenuController', ['except' => ['show']]); // 菜单
	$router->get('menus/{menu_id}', 'Category\MenuItemController@index'); // 菜单项 列表
	$router->resource('menu_items', 'Category\MenuItemController', ['except' => ['index', 'create']]); // 菜单项
	$router->get('menu_items/create/{menu_id}', 'Category\MenuItemController@create'); // 菜单项 创建
	$router->resource('categories', 'Category\CategoryController'); // 分类
	$router->resource('tags', 'Category\TagController'); // 标签
	// ### Application 应用
	$router->resource('pages', 'Application\PageController'); // 页面
	$router->resource('posts', 'Application\PostController'); // 文章
	$router->get('notes/{id}/renew', 'Application\NoteController@edit'); // 笔记
	$router->resource('notes', 'Application\NoteController'); // 笔记
	$router->resource('links', 'Application\LinkController'); // 链接
	$router->resource('pictures', 'Application\PictureController'); // 图片
	$router->resource('accounts', 'Application\AccountController'); // 账号
	$router->resource('comments', 'Application\CommentController'); // 评论

	// ### 商城
	// ### 外卖

	// ### 医院

	// ### 应用
	$router->resource('songs', 'Music\SongController'); // 歌曲
	$router->resource('singers', 'Music\SingerController'); // 歌手
	$router->resource('albums', 'Music\AlbumController'); // 专辑

	// ### 工具
	$router->resource('words', 'Tool\WordController');
	$router->get('compile/index', 'Tool\CompileController@index');

	// ### 下载
	$router->get('export/{category}-{slug}', 'Common\DownloadController@export'); // 导出
	$router->get('queue-export/{category}-{slug}', 'Common\DownloadController@queueExport'); // 队列导出
	$router->get('store/{category}-{slug}', 'Common\DownloadController@store'); // 保存
	$router->get('import/{category}-{slug}', 'Common\DownloadController@import'); // 导入
	$router->get('queue-import/{category}-{slug}', 'Common\DownloadController@queueImport'); // 队列导入
	$router->get('download/{slug}', 'Common\DownloadController@index'); // 下载
});
