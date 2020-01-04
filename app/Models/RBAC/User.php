<?php
namespace App\Models\RBAC;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable { // RBAC - 用户
    use SoftDeletes;
    use ActionButtonTrait;
    use Notifiable;

    protected $table = 'rbac_users';
    protected $fillable = [
        'phone',
        'email',
        'name',
        'description',
        'avatar',
        'corporation_id',
        'password',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 关联
    public function corporation() { // 公司
        return $this->belongsTo('App\Models\Architecture\Corporation');
    }
    public function sites() { // 站点
        return $this->belongsToMany('App\Models\Architecture\Site', 'architecture_user_site');
    }
    public function departments() { // 部门
        return $this->belongsToMany('App\Models\Architecture\Department', 'architecture_user_department');
    }
    public function roles() { // 角色
        return $this->belongsToMany('App\Models\RBAC\Role', 'created_by');
    }
    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function profile() { // 个人信息 一对一
        return $this->hasOne('App\Models\RBAC\Profile');
    }
}
