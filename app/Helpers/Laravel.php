<?php
// ## Laravel
use App\Models\System\Menu;
use App\Models\System\MenuItem;

// ### System
function route_class() {
    return str_replace('.', '-', Route::currentRouteName());
}
function base_route() {
    $route_name = \Route::currentRouteName();
    $route_name_data = explode('.', $route_name);
    return reset($route_name_data);
}

// ### 菜单
function menu($slug=1) {
	$menu = Menu::find($slug)->orWhere('slug', $slug);
	return $menu->items();
}
