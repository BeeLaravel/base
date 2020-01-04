<?php
namespace App\Models\Application;

class Note extends Model {
    protected $table = 'application_notes';
    protected $fillable = [ // 自动填充
        'title',
        'content',
        'category_id',
        'sort',
        'created_by',
    ];

    // 关联
    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
    public function category() { // 分类 一对多 反向
        return $this->belongsTo('App\Models\Category\Category');
    }
    public function tags() { // 标签 多对多 反向
        return $this->belongsToMany('App\Models\Category\Tag', 'category_user_tag', 'id')->wherePivot('table', 'notes');
    }
}
