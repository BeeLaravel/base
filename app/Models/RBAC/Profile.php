<?php
namespace App\Models\RBAC;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Admin\ActionButtonTrait;

class Profile extends Model { // RBAC 用户信息
    use ActionButtonTrait;

    protected $table = 'rbac_profiles';
    protected $primaryKey = 'user_id';
    protected $touches = ['user'];

    protected $fillable = [
        'qq',
        'wechat',
        'weibo',
        'github',
        'gitee',

        'categories',
        'tags',

        'description',
    ];

    // 关联
    public function user() { // 用户 一对一 反向
        return $this->belongsTo('App\Models\RBAC\User');
    }
}
