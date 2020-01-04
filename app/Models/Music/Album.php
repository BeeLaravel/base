<?php
namespace App\Models\Music;

class Album extends Model {
	protected $table = 'music_albums';
    protected $fillable = ['title', 'published_at', 'description', 'sort', 'created_by', 'created_at', 'updated_at'];

    public function creater() { // 创建人 一对多 反向
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

	public function category() { // 分类 一对多 反向
        return $this->belongsTo('App\Models\Category\Category');
    }
    public function tags() { // 标签 多对多 反向
        return $this->belongsToMany('App\Models\Category\Tag', 'category_tag_morph', 'id');
    }
}
