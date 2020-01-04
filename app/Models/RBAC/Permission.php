<?php
namespace App\Models\RBAC;

class Permission extends Model { // RBAC - 结点
    protected $table = 'rbac_permissions';
    protected $fillable = [
        'parent_id',
    	'slug',
        'title',
        'description',
        'sort',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // 关联
    public function roles() { // 角色
        return $this->belongsToMany('App\Models\RBAC\Role', 'rbac_role_permission');
    }
    public function parent() { // 父级 一对多 反向
        return $this->belongsTo('App\Models\RBAC\Permission', 'parent_id');
    }
    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
}
