<?php
namespace App\Models\Configure;

class Configure extends Model {
    // protected $table = 'configures';
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
