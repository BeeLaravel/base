<?php
namespace App\Models\Application;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
	protected $table = 'application_comments';
    protected $fillable = [
    	// '',
    	// '',
        'parent_id',
        'content',
        'created_by',
    ];

    public static $types = [
        'pages' => 'pages',
        'posts' => 'posts',
        'links' => 'links',
        'pictures' => 'pictures',
        'comments' => 'comments',
    ];

	public function parent() { // 父级 一对多 反向
        return $this->belongsTo(self::class, 'parent_id');
    }
    public function children() { // 子级 一对多
        return $this->hasMany(self::class, 'parent_id');
    }
    public function user() { // 创建人 一对多 反向
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

	public function category() { // 分类 一对多 反向
        return $this->belongsTo('App\Models\Category\Category');
    }
    public function tag() { // 标签 一对多 反向
        return $this->belongsTo(Tag::class);
    }
    public function link() { // 链接 一对多 反向
        return $this->belongsTo('App\Models\Application\Link');
    }
    public function post() { // 文章 一对多 反向
        return $this->belongsTo('App\Models\Application\Post');
    }
    public function page() { // 页面 一对多 反向
        return $this->belongsTo('App\Models\Application\Page');
    }
}
