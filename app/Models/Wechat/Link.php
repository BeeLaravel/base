<?php
namespace App\Models\Wechat;

class Link extends Model {
    protected $table = 'wechat_menu_links';

    public function menus() { // Menu 一对多反向
        return $this->belongsTo('App\Models\Wechat\Menu');
    }
    public function creater() { // 创建人 一对一
        return $this->hasOne('App\Models\RBAC\User', 'created_by');
    }
}
