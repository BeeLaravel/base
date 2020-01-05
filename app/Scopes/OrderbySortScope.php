<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderbySortScope implements Scope {
    public function apply(Builder $builder, Model $model) {
        $builder->orderBy('sort', 'asc');
    }
}
