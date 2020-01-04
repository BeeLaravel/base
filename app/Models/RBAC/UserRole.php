<?php
namespace App\Models\RBAC;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model { // RBAC - 用户角色
	protected $table = 'rbac_user_role';
    protected $fillable = [
    	'user_id',
        'role_id',
    ];
}
