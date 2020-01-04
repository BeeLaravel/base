<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class Gallery extends Model
{
    use SoftDeletes;
    use ActionButtonTrait;

    protected $table = 'cms_galleries';
    protected $fillable = [
        'parent_id',
        'slug',
        'title',
        'description',
        'sort',
        'user_id',
    ];

    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User');
    }
}
