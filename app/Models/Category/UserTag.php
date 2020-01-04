<?php
namespace App\Models\Application;

class UserTag extends \Illuminate\Database\Eloquent\Relations\Pivot {
	protected $table = 'category_user_tag';
}
