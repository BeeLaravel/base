<?php
namespace App\Models\Application;

class Page extends Model {
    protected $table = 'application_pages';
    protected $fillable = [ // 自动填充
        'title',
        'slug',
        'keywords',
        'description',
        'content',
        'category_id',
        'sort',
        'created_by',
    ];
    protected $casts = [ // 类型转换
        'keywords' => 'array',
    ];
    public static $types = [
        'MarkDown' => 'MarkDown',
        'reStructuredText' => 'reStructuredText',
        'HTML' => 'HTML',
        'TinyMCE' => 'TinyMCE',
        'UEditor' => 'UEditor',
    ];

    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function category() { // 分类 一对多 反向
        return $this->belongsTo('App\Models\Category\Category');
    }
    public function tags() { // 标签 多对多 反向
        return $this->belongsToMany('App\Models\Category\Tag', 'category_user_tag');
    }
}
