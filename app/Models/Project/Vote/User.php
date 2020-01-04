<?php
namespace App\Models\Vote;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class User extends Model {
    use SoftDeletes;
    use ActionButtonTrait;

    protected $table = 'vote_users';
    protected $fillable = [
        // 'parent_id',
        // 'slug',
        // 'title',
        // 'description',
        // 'sort',
        // 'user_id',
    ];

    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User');
    }
    public function vote() { // 投票
        return $this->belongsTo(Vote::class);
    }
    public function logs() { // 日志
        return $this->hasMany(Log::class);
    }
}
