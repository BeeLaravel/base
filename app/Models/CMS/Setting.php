<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class Setting extends Model
{
    use SoftDeletes;
    use ActionButtonTrait;

    protected $table = 'cms_settings';
    protected $fillable = [
        'key',
        'default_value',
        'description',
        'sort',
        'user_id',
    ];

    public function settingValue() { // 值
        return $this->hasMany('App\Models\CMS\Setting', 'key', 'key');
    }
    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User');
    }
}
