<?php
namespace App\Models\Vote;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class Vote extends Model {
    use SoftDeletes;
    use ActionButtonTrait;

    protected $fillable = [
        // 'slug',
        // 'title',
        // 'url',
        // 'type',
        // 'category_id',
        // 'description',
        // 'sort',
        // 'user_id',
    ];
    // public static $types = [
    //     'Site' => '站点',
    //     'SubSite' => '子站点',
    //     'Special' => '专题',
    //     'Category' => '分类',
    //     'Tag' => '标签',
    //     'Post' => '文章',
    //     'Discuss' => '讨论',
    //     'Other' => '其它',
    // ];

    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User');
    }
    public function users() { // 参赛人
        return $this->hasMany(User::class);
    }
}
