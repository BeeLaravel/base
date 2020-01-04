<?php
namespace App\Models\Vote;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class Log extends Model {
    use SoftDeletes;
    use ActionButtonTrait;

    protected $table = 'vote_logs';
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
    public function vote_user() { // 投给
        return $this->belongsTo(User::class);
    }
}
