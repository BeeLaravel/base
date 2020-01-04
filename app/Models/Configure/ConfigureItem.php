<?php
namespace App\Models\Configure;

class ConfigureItem extends Model {
    // protected $table = 'configure_items';
    protected $fillable = [
        'slug',
        'title',
        'description',
        'sort',
        'created_by',
    ];

    public function user() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
}
