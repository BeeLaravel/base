<?php
namespace App\Models\RBAC;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model { // RBAC - 角色权限
	protected $table = 'rbac_role_permission';
    protected $fillable = [
        'role_id',
    	'permission_id',
    ];
}
