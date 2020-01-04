<?php
// ## File

// 路径
// function resource_path($path) { // 资源路径
// 	return storage_path('resource/'.$path);
// }
function template_path($path) { // 模板路径
	return storage_path('templates/'.$path);
}
function temp_path($path) { // 临时路径
	return storage_path('temp/'.$path);
}
