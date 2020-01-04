<?php
namespace App\Models\Tool;

class File extends Model {
	protected $table = 'resource_files';
    protected $fillable = ['title', 'extension', 'mime', 'size', 'category', 'url', 'md5', 'sha1', 'created_by', 'created_at', 'updated_at'];
}
