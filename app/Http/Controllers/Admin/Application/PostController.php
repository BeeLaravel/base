<?php
namespace App\Http\Controllers\Admin\Application;

use Illuminate\Http\Request;

use App\Models\Application\Post as ThisModel;
use App\Http\Requests\Application\PostRequest as ThisRequest;

use App\Models\Application\Content;

class PostController extends Controller {
    protected $baseInfo = [
        'slug' => 'posts',
        'title' => '文章',
        'description' => '文章列表',
        'link' => '/admin/posts',
        'parent_title' => '应用',
        'parent_link' => '/admin/posts',
        'view_path' => 'admin.application.post.',
    ];
    private $show = [
        'id',
        'title',
        'slug',
        'type',
        'created_at',
        'updated_at',
    ];

    public function __construct() {
        $this->model = new ThisModel;

        parent::__construct();
    }
    public function index(Request $request) {
        if ( $request->ajax() ) {
            $draw = $request->input('draw', 1);
            $start = $request->input('start', 0);
            $length = $request->input('length', 20);

            $order['name'] = $request->input('columns.' .$request->input('order.0.column') . '.name', 'id'); // 排序字段
            $order['dir'] = $request->input('order.0.dir', 'desc'); // 升降序
            $search['value'] = $request->input('search.value', ''); // 搜索字段
            $search['regex'] = $request->input('search.regex', false); // 是否正则

            $model = ThisModel::where('created_by', auth('admin')->user()->id);

            // # 搜索
            $columns = $request->input('columns');
            foreach ( $columns as $key => $value ) { // 字段搜索
                if ( $value['search']['value'] ) {
                    switch ( $key ) {
                        default:
                            if ( $value['search']['value'] ) { // 有内容
                                if ( $value['search']['regex']=='true' ) { // 正则
                                    $model = $model->where($this->show[$key], 'like', "%{$value['search']['value']}%");
                                } else { // 普通
                                    $model = $model->where($this->show[$key], $value['search']['value']);
                                }
                            }
                    }
                }
            }

            if ( $search['value'] ) { // 搜索
                if ( $search['regex'] == 'true' ) { // 正则匹配
                    $model = $model->where('slug', 'like', "%{$search['value']}%")
                        ->orWhere('title', 'like', "%{$search['value']}%")
                        ->orWhere('keywords', 'like', "%{$search['value']}%")
                        ->orWhere('description', 'like', "%{$search['value']}%");
                } else { // 完全匹配
                    $model = $model->where('slug', $search['value'])
                        ->orWhere('title', $search['value'])
                        ->orWhere('keywords', $search['value'])
                        ->orWhere('description', $search['value']);
                }
            }

            $count = $model->count(); // 总数
            $model = $model->orderBy($order['name'], $order['dir']); // 排序
            $model = $model->offset($start)->limit($length)->get(); // 分页

            if ( $model ) {
                foreach ( $model as $item ) {
                    $item->button = $item->getActionButtons($this->baseInfo['slug']);
                }
            }

            return [
                'draw' => $draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => $model,
            ];
        } else {
            $types = auth('admin')->user()->profile->posts ?? '[]';
            $types = json_decode($types, true);

            $content_types = Content::$types;

            $search = [
                'type' => '',
                'category_id' => 0,
            ];

            // $tags = Tag::pluck('title', 'id');

            return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('types', 'content_types', 'search', 'tags')));
        }
    }
}
