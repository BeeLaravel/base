<?php
namespace App\Models\Architecture;

use Illuminate\Database\Eloquent\Model;

class UserSite extends Model { // 架构 - 用户站点
	protected $table = 'architecture_user_site';
    protected $fillable = [
    	'user_id',
        'site_id',
    ];
}
