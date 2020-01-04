<?php
namespace App\Models\Category;

class Tag extends Model {
    protected $table = 'category_tags';
    protected $fillable = [
        'title',
        'slug',
        'type',
        'description',
        'created_by',
    ];

    public function creater() { // 创建人 一对多 反向
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function pages() { // 页面 多对多
        return $this->belongsToMany('App\Models\Application\Page');
    }
    public function posts() { // 文章 多对多
        return $this->belongsToMany('App\Models\Application\Post');
    }
    public function links() { // 链接 多对多
        return $this->belongsToMany('App\Models\Application\Link');
    }
}
