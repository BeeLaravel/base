<?php
namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Admin\ActionButtonTrait;

class Configure extends Model { // 用户 - 配置
    use ActionButtonTrait;

    protected $table = 'user_configures';
    protected $primaryKey = 'user_id';
    protected $touches = ['user'];

    protected $fillable = [
        'categories',
        'tags',
        'notes',
        'posts',
        'pages',
        'pictures',
        'links',
        'accounts',
    ];

    // 关联
    public function user() { // 用户 一对一 反向
        return $this->belongsTo('App\Models\RBAC\User');
    }
}
