<?php
namespace App\Models\Category;

class Category extends Model {
    protected $table = 'category_categories';
    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'type',
        'description',
        'sort',
        'created_by',
    ];

    public function parent() { // 父级 一对多 反向
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function children() { // 子级 一对多
        return $this->hasMany(self::class, 'parent_id');
    }
    public function creater() { // 创建人 一对多 反向
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function links() { // 链接 一对多
        return $this->hasMany('App\Models\Application\Link');
    }
    public function posts() { // 文章 一对多
        return $this->hasMany('App\Models\Application\Post');
    }
    public function pages() { // 页面 一对多
        return $this->hasMany('App\Models\Application\Page');
    }
}
