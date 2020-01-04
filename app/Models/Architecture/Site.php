<?php
namespace App\Models\Architecture;

class Site extends Model { // 架构 - 站点
    protected $table = 'architecture_sites';
    protected $fillable = [
        'slug',
        'title',
        'description',
        'address',
        'tel',
        'postcode',
        'parent_id',
        'sort',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // 属性
    // 关联
    public function departments() { // 部门
        return $this->hasMany('App\Models\Architecture\Department');
    }
    public function corporation() { // 公司
        return $this->belongsTo('App\Models\Architecture\Corporation');
    }
    public function users() { // 员工
        return $this->belongsToMany('App\Models\RBAC\User');
    }
    public function parent() { // 父级
        return $this->belongsTo('App\Models\Architecture\Site');
    }
    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
}
