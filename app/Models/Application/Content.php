<?php
namespace App\Models\Application;

use Illuminate\Database\Eloquent\Model;

class Content extends Model {
    protected $table = 'application_post_contents';
    protected $fillable = [
        'post_id',
        'content_type',
        'content',
    ];

    public static $types = [
        'MarkDown' => 'MarkDown',
        'reStructuredText' => 'reStructuredText',
        'HTML' => 'HTML',
        'TinyMCE' => 'TinyMCE',
        'UEditor' => 'UEditor',
    ];

    public function post() { // 内容 一对一 反向
        return $this->belongsTo('App\Models\Application\Post');
    }
}
