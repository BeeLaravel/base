<?php
namespace App\Models\Tool;

class Word extends Model {
    protected $table = 'tool_words';
    protected $fillable = [
        'title',
        'language',
        'description',
        'sort',
        'created_by',
    ];

    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }
}
