<?php
namespace App\Models\Configure;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class Model extends \Illuminate\Database\Eloquent\Model {
    use SoftDeletes;
    use ActionButtonTrait;
}
