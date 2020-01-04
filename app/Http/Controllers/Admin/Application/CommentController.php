<?php
namespace App\Http\Controllers\Admin\Application;

use App\Models\Application\Comment as ThisModel;
use App\Models\Category\Category;
use App\Models\Category\Tag;

use Illuminate\Http\Request;
use App\Http\Requests\Application\CommentRequest as ThisRequest;

class CommentController extends Controller {
    private $baseInfo = [
        'slug' => 'comments',
        'title' => '评论',
        'description' => '评论列表',
        'link' => '/admin/comments',
        'parent_title' => '应用',
        'parent_link' => '/admin/posts',
        'view_path' => 'admin.application.comment.',
    ];

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
                        case 3: // parent_id
                            $model = $model->where('parent_id', $value['search']['value']);
                        break;
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
                        ->orWhere('description', 'like', "%{$search['value']}%");
                } else { // 完全匹配
                    $model = $model->where('slug', $search['value'])
                        ->orWhere('title', $search['value'])
                        ->orWhere('description', $search['value']);
                }
            }

            $count = $model->count(); // 总数
            $model = $model->orderBy($order['name'], $order['dir']); // 排序
            $model = $model->offset($start)->limit($length)->get(); // 分页

            if ( $model ) {
                foreach ( $model as $item ) {
                    // $item->user_name = $item->created_by ? $item->user->name : '未知';
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
            $types = ThisModel::$types;
            $search = [
                'type' => '',
                'category_id' => 0,
            ];
            $tags = Tag::pluck('title', 'id');

            return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('search', 'types', 'tags')));
        }
    }
    public function create(Request $request) {
        $types = ThisModel::$types;
        $tags = Tag::get();
        $tags = level_array($tags);
        $tags = plain_array($tags, 0, "==");
        
        // $tags = Tag::pluck('title');
        // $tags = json_encode($tags);
        // $keywords = Keyword::pluck('title');
        // $keywords = json_encode($keywords);

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('types', 'tags')));
    }
    public function store(ThisRequest $request) {
        $result = Post::create(array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id,
        ]));

        if ( $result ) {
            $result->content()->save(new Content([
                'content' => $request->content,
                'content_type' => $request->type,
            ]));
            // $tags = $request->input('tags');
            // log_file($tags);
            // $exist_tags = Tag::where('title', 'in', $tags)->pluck('title', 'id');
            // log_file($exist_tags, 'exist_tags');
            // $not_exist_tags = array_diff($tags, $exist_tags);
            // log_file($not_exist_tags, 'not_exist_tags');

            // if ( $not_exist_tags ) {
            //     $temp = [];
            //     foreach ( $not_exist_tags as $tag ) {
            //         $temp[] = [
            //             'slug' => str_slug($tag),
            //             'title' => $tag,
            //             'user_id' => 0, // todo user_id slug
            //         ];
            //     }
            //     $create_result = Tag::create($temp);
            //     log_file($create_result);
            // }

            
        }

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect('/admin/pages'); // 列表
        } else {
            flash('操作失败', 'error');

            return back(); // 继续
        }
    }
    public function show(int $id) {}
    public function edit(int $id) {
        $types = ThisModel::$types;

        // $tags = Tag::get();
        // $tags = level_array($tags);
        // $tags = plain_array($tags, 0, "==");

        $tags = Tag::pluck('title');
        $tags = json_encode($tags);

        $item = ThisModel::find($id);

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('types', 'tags', 'item')));
    }
    public function update(ThisRequest $request, int $id) {
        $post = ThisModel::find($id);
        $result = $post->update($request->all());

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['link']); // 列表
        } else {
            flash('操作失败', 'error');
            $error = back()->withErrors();
            dd($error);
            // return back(); // 继续
        }
    }
    public function destroy(Request $request, int $id) {
        $result = ThisModel::destroy($id);

        if ( $result ) {
            flash('删除成功', 'success');
        } else {
            flash('删除失败', 'error');
        }

        return redirect($this->baseInfo['link']);
    }
}
