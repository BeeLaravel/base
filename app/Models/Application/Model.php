<?php
namespace App\Models\Application;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class Model extends \Illuminate\Database\Eloquent\Model {
    use SoftDeletes;
    use ActionButtonTrait;
}
