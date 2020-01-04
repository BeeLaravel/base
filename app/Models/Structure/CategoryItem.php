<?php
namespace App\Models\Structure;

use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model {
	// ### 关联
	public function Category() { // 分类项 一对多
        return $this->belongsTo('App\Models\Structure\Category');
    }
    public function parent() { // 父级 一对多
        return $this->belongsTo('App\Models\Structure\CategoryItem', 'parent_id');
    }
    public function children() { // 子级 一对多 反向
        return $this->belongsTo('App\Models\Structure\CategoryItem', 'parent_id');
    }
	public function creater() { // 创建人 一对一
        return $this->hasOne('App\Models\RBAC\User', 'created_by');
    }
    // #### 
    public function links() { // 链接 一对多 反向
        return $this->hasMany('App\Models\Application\Link', 'category_id');
    }
}
