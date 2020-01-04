<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class SettingValue extends Model
{
    use SoftDeletes;
    use ActionButtonTrait;

    protected $table = 'cms_setting_values';
    protected $fillable = [
        'key',
        'value',
        'organization_id',

        'sort',
        'user_id',
    ];


    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User');
    }
}
