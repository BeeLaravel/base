<?php
namespace App\Models\Category;

class Menu extends Model {
	protected $table = 'category_menus';
	protected $fillable = [
        'title',
        'slug',
        'type',
        'description',
        'sort',
        'created_by',
    ];

	// 关联
	public function items() { // 菜单项 一对多
        return $this->hasMany('App\Models\Category\MenuItem');
    }
	public function creater() { // 创建人 一对多 反向
        return $this->hasOne('App\Models\RBAC\User', 'created_by');
    }
}
