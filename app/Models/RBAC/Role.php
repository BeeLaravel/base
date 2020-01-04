<?php
namespace App\Models\RBAC;

class Role extends Model { // RBAC 角色
    protected $table = 'rbac_roles';
    protected $fillable = [
        'slug',
        'title',
        'description',
        'sort',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $hidden = [];

    // 关联
    public function permissions() { // 权限
        return $this->belongsToMany('App\Models\RBAC\Permission', 'rbac_role_permission');
    }
    public function users() { // 用户
        return $this->belongsToMany('App\Models\RBAC\User');
    }
    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
}
