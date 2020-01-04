<?php
namespace App\Models\Application;

class Post extends Model {
    protected $table = 'application_posts';
    protected $fillable = [ // 自动填充
        'title',
        'slug',
        'keywords',
        'description',
        'category_id',
        'sort',
        'created_by',
    ];
    protected $casts = [ // 类型转换
        'keywords' => 'array',
    ];

    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function category() { // 分类 一对多 反向
        return $this->belongsTo('App\Models\Category\Category');
    }
    public function tags() { // 标签 多对多 反向
        return $this->belongsToMany(Tag::class, 'category_user_tag');
    }
    public function content() { // 内容 一对一
        return $this->hasOne('App\Models\Application\Content')->withDefault();
    }
}
