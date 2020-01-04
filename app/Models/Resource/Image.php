<?php
namespace App\Models\Resource;

class Image extends Model {
	// protected $table = 'images';
	protected $table = 'cases';

    public static $rules = [
        'file' => 'required|mimes:png,gif,jpeg,jpg,bmp',
    ];
    public static $messages = [
        'file.required' => '图片必须上传',
        'file.mimes' => '上传的文件不是图片',
    ];
}
