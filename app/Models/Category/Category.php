<?php
namespace App\Models\Category;

class Category extends Model {
    protected $table = 'category_categories';
    protected $fillable = [
        'type',
        'parent_id',
        'slug',
        'title',
        'description',
        'sort',
        'created_by',
    ];

    public static function getCategories($slug) {
        $items = self::where('created_by', auth('admin')->user()->id)
            ->whereIn('type', ['default', $slug])
            ->get();
        $items = level_array($items);
        $items = plain_array($items, 0, '==');

        return $items;
    }

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
    public function notes() { // 笔记 一对多
        return $this->hasMany('App\Models\Application\Note');
    }
}
