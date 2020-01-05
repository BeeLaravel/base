<?php
namespace App\Models\Category;

use Illuminate\Support\Str;

class Tag extends Model {
    protected $table = 'category_tags';
    protected $fillable = [
        'type',
        'slug',
        'title',
        'description',
        'created_by',
    ];

    public static function getTags($slug) {
        $items = self::where('created_by', auth('admin')->user()->id)
            ->whereIn('type', ['commons', $slug])
            ->pluck('title');
        $items = json_encode($items);

        return $items;
    }
    public static function setTags($slug, $items) {
        $exist_items = self::where('created_by', auth('admin')->user()->id)
            ->whereIn('type', ['commons', $slug])
            ->whereIn('title', $items)
            ->pluck('title', 'id')->toArray();
        $not_exist_items = array_diff($items, $exist_items);

        if ( $not_exist_items ) {
            $temp = [];
            foreach ( $not_exist_items as $item ) {
                $temp[] = [
                    'title' => $item,
                    'slug' => Str::slug($item),
                    'type' => 'commons',
                    'created_by' => auth('admin')->user()->id,
                ];
            }
            $created_count = self::insert($temp);
        }

        $items = self::where('created_by', auth('admin')->user()->id)
            ->whereIn('type', ['commons', $slug])
            ->whereIn('title', $items)
            ->pluck('id')
            ->toArray();
        $items = array_combine($items, array_fill(0, count($items), ['table' => $slug]));

        return $items;
    }

    public function creater() { // 创建人 一对多 反向
        return $this->belongsTo('App\Models\RBAC\User', 'created_by');
    }

    public function pages() { // 页面 多对多
        return $this->belongsToMany('App\Models\Application\Page');
    }
    public function posts() { // 文章 多对多
        return $this->belongsToMany('App\Models\Application\Post');
    }
    public function links() { // 链接 多对多
        return $this->belongsToMany('App\Models\Application\Link');
    }
    public function pictures() { // 图片 多对多
        return $this->belongsToMany('App\Models\Application\Pictures');
    }
}
