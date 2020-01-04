<?php
namespace App\Models\Architecture;

class Corporation extends Model { // 架构 - 公司
    protected $table = 'architecture_corporations';
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
    public function sites() { // 站点
        return $this->hasMany('App\Models\Architecture\Site');
    }
    public function departments() { // 部门
        return $this->hasMany('App\Models\Architecture\Department');
    }
    public function users() { // 员工
        return $this->hasMany('App\Models\RBAC\User');
    }
    public function parent() { // 父级
        return $this->belongsTo('App\Models\Architecture\Corporation');
    }
    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
}
