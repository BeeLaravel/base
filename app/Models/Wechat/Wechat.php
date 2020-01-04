<?php
namespace App\Models\Wechat;

// 微信公众号
class Wechat extends Model {
	protected $table = 'wechats';

	protected $fillable = [
        'title',
        'slug',
        'description',
        'sort',
    ];
	// 关联
	public function corporation() { // 公司 一对多反向
        return $this->belongsTo('App\Models\RBAC\Corporation');
    }
    public function menus() { // Menu 一对多
        return $this->hasMany('App\Models\Wechat\Menu');
    }
    public function users() { // User 一对多
        return $this->hasMany('App\Models\Wechat\User');
    }
    public function creater() { // 创建人 一对一
        return $this->hasOne('App\Models\RBAC\User', 'created_by');
    }
}
