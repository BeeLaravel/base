<?php
namespace App\Models\Tool;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Admin\ActionButtonTrait;

class Model extends \App\Models\Model {
    use SoftDeletes;
    use ActionButtonTrait;
}
