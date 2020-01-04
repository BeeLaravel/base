<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class Menu extends Model
{
    use SoftDeletes;
    use ActionButtonTrait;

    protected $table = 'cms_menus';
    protected $fillable = [
        'slug',
        'title',
        'description',
        'parent_id',
        'organization_id',
        'sort',
        'user_id',
    ];

    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User');
    }
}
