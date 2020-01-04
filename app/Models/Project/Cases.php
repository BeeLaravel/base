<?php
namespace App\Models\Project;

class Cases extends Model {
	// 关联
	public function department() { // 科室 一对多 反向
        return $this->belongsTo('App\Models\Structure\CategoryItem');
    }
    public function project() { // 项目 一对多 反向
        return $this->belongsTo('App\Models\Structure\CategoryItem');
    }
    public function doctor() { // 医师 一对多 反向
        return $this->belongsTo('App\Models\Structure\CategoryItem');
    }
    public function star() { // 星级 一对多 反向
        return $this->belongsTo('App\Models\Structure\CategoryItem');
    }
}
