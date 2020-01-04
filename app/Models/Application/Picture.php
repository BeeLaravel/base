<?php
namespace App\Models\Application;

class Picture extends Model {
    protected $table = 'application_pictures';
    protected $fillable = [
        'title',
        'image',
        'type',
        'category_id',
        'description',
        'sort',
        'created_by',
    ];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope(new \App\Scopes\OrderbySortScope);
    }

    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function category() { // 分类 一对多 反向
        return $this->belongsTo('App\Models\Category\Category', 'category_id');
    }
    public function tags() { // 标签 多对多 反向
        return $this->belongsToMany('App\Models\Category\Tag', 'category_user_tag', 'id')->wherePivot('table', 'pictures');
    }
}
