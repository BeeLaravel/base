<?php
namespace App\Models\Structure;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
	// 关联
	public function items() { // 菜单项 一对多
        return $this->hasMany('App\Models\Structure\CategoryItem');
    }
	public function creater() { // 创建人 一对一
        return $this->hasOne('App\Models\RBAC\User', 'created_by');
    }
}
