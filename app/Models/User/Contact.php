<?php
namespace App\Models\RBAC;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Admin\ActionButtonTrait;

class Contact extends Model { // 用户 - 联系方式
    use ActionButtonTrait;

    protected $table = 'user_contacts';
    protected $primaryKey = 'user_id';
    protected $touches = ['user'];

    protected $fillable = [
        'slug',
        'content',
    ];

    // 关联
    public function user() { // 用户 一对一 反向
        return $this->belongsTo('App\Models\RBAC\User');
    }
}
