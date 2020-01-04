<?php
namespace App\Models\Configure;

class ConfigureTemplate extends Model {
    // protected $table = 'configure_templates';
    protected $fillable = [
        'title',
        'slug',
        'type',
        'description',
        'created_by',
    ];

    public function user() { // 创建人 一对多 反向
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
}
