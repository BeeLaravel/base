<?php
namespace App\Models\Architecture;

class Department extends Model { // 架构 - 部门
    protected $table = 'architecture_departments';
    protected $fillable = [
        'slug',
        'title',
        'description',
        'address',
        'tel',
        'parent_id',
        'corporation_id',
        'sort',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // 属性
    // 关联
    public function sites() { // 部门
        return $this->hasMany('App\Models\Architecture\Site');
    }
    public function corporation() { // 公司
        return $this->belongsTo('App\Models\Architecture\Corporation');
    }
    public function users() { // 员工
        return $this->belongsToMany('App\Models\RBAC\User');
    }
    public function parent() { // 父级
        return $this->belongsTo('App\Models\Architecture\Department', 'parent_id');
    }
    public function children() { // 子级
        return $this->hasMany('App\Models\Architecture\Department', 'parent_id');
    }
    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
}
