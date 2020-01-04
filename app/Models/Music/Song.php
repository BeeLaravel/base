<?php
namespace App\Models\Music;

class Song extends Model {
	protected $table = 'music_songs';
    protected $fillable = ['title', 'poem', 'lyric', 'description', 'category_id', 'type', 'sort', 'created_by', 'created_at', 'updated_at'];
    protected $appends = [
    ];

    public function creater() { // 创建人
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function category() { // 分类 一对多 反向
        return $this->belongsTo('App\Models\Category\Category', 'category_id');
    }
    public function tags() { // 标签 多对多 反向
        return $this->belongsToMany('App\Models\Category\Tag', 'category_user_tag', 'id')->wherePivot('table', 'links');
    }

    public function getPasswdAttribute() {
        if ( substr($this->password, 0, 3)=='la_' ) {
            $method = "DES-ECB"; // DES-ECB|AES-128-CBC
            $password = "beesoft";
            return openssl_decrypt(substr($this->password, 3), $method, $password);
        } else {
            return $this->password;
        }
    }
    public function setPasswdAttribute($value) {
        if ( $value ) {
            $method = "DES-ECB"; // DES-ECB|AES-128-CBC
            $password = "beesoft";
            $this->password = "la_".openssl_encrypt($value, $method, $password);
        } else {
            $this->password = '';
        }
    }
}
