<?php
namespace App\Models\Architecture;

use Illuminate\Database\Eloquent\Model;

class UserDepartment extends Model { // 架构 - 用户部门
	protected $table = 'architecture_user_department';
    protected $fillable = [
    	'user_id',
        'department_id',
    ];
}
