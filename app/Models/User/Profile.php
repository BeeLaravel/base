<?php
namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Admin\ActionButtonTrait;

class Profile extends Model { // 用户 - 信息
    use ActionButtonTrait;

    protected $table = 'user_profiles';
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
