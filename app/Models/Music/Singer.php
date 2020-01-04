<?php
namespace App\Models\Music;

class Singer extends Model {
	protected $table = 'music_singers';
    protected $fillable = ['title', 'gender', 'birthday', 'description', 'sort', 'created_by', 'created_at', 'updated_at'];
    protected $appends = [
    ];

    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function category() { // 分类 一对多 反向
        return $this->belongsTo('App\Models\Category\Category', 'category_id');
    }
    public function tags() { // 标签 多对多 反向
        return $this->belongsToMany('App\Models\Category\Tag', 'category_user_tag', 'id')->wherePivot('table', 'links');
    }
}
