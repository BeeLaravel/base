<?php
namespace App\Models\Wechat;

class Menu extends Model {
	protected $table = 'wechat_menus';

	// 关联
	public function wechat() { // 微信 一对多反向
        return $this->belongsTo('App\Models\Wechat\Wechat');
    }
	public function links() { // 链接 一对多
        return $this->hasMany('App\Models\Wechat\Link');
    }
	public function creater() { // 创建人 一对一
        return $this->hasOne('App\Models\RBAC\User', 'created_by');
    }
}
