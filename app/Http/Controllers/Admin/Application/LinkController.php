<?php
namespace App\Http\Controllers\Admin\Application;

use App\Models\Application\Link as ThisModel;
use App\Models\Category\Category;
use App\Models\Category\Tag;

use Illuminate\Http\Request;
use App\Http\Requests\Application\LinkRequest as ThisRequest;

/**
 * @OA\Info(
 *     version="3.0",
 *     title="Application-Link | 应用-链接",
 *     @OA\Contact(
 *         name="BeeSoft",
 *         url="https://www.beesoft.ink",
 *         email="beherochuling@163.com"
 *     )
 * ),
 * @OA\Server(
 *     url="https://laravel56.beesoft.ink/admin"
 * ),
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     description="Use a global client_id / client_secret and your email / password combo to obtain a token",
 *     name="passport",
 *     in="header",
 *     scheme="http",
 *     securityScheme="passport",
 *     @OA\Flow(
 *         flow="password",
 *         authorizationUrl="/oauth/authorize",
 *         tokenUrl="/oauth/token",
 *         refreshUrl="/oauth/token/refresh",
 *         scopes={}
 *     )
 * )
 */
class LinkController extends Controller {
    private $baseInfo = [
        'slug' => 'links',
        'title' => '链接',
        'description' => '链接列表',
        'link' => '/admin/links',
        'parent_title' => '应用',
        'parent_link' => '/admin/',
        'view_path' => 'admin.application.link.',
    ];
    private $show = [
        'id',
        'title',
        'type',
        'url',
        'created_at',
        'updated_at',
    ];

    /**
     * @OA\Get(
     *     path="/links",
     *     operationId="getLinkList",
     *     tags={"Links"},
     *     summary="Get list of Link",
     *     description="Returns list of links",
     *     @OA\Parameter(
     *         name="Accept",
     *         description="Accept header to specify api version",
     *         required=false,
     *         in="header",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         description="The page num of the list",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         description="The item num per page",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function index(Request $request) {
        if ( $request->ajax() ) {
            $draw = $request->input('draw', 1);
            $start = $request->input('start', 0);
            $length = $request->input('length', config('beesoft.page.size'));

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
                    $model = $model->where('url', 'like', "%{$search['value']}%")
                        ->orWhere('title', 'like', "%{$search['value']}%")
                        ->orWhere('description', 'like', "%{$search['value']}%");
                } else { // 完全匹配
                    $model = $model->where('url', $search['value'])
                        ->orWhere('title', $search['value'])
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
            $types = auth('admin')->user()->profile->links ?? '[]';
            $types = json_decode($types, true);

            $search = [
                'type' => $request->cookie('type') ?: '',
            ];

            return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('types', 'search')));
        }
    }
    public function create(Request $request) {
        $types = auth('admin')->user()->profile->links;
        $types = json_decode($types, true);

        $category_array = Category::where('created_by', auth('admin')->user()->id)->where('type', 'commons')->get();
        $categories = level_array($category_array);
        $categories = plain_array($categories, 0, '==');

        $tags = Tag::where('created_by', auth('admin')->user()->id)->whereIn('type', ['commons', 'links'])->pluck('title');
        $tags = json_encode($tags);

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('types', 'categories', 'tags')));
    }
    /**
     * @OA\Post(
     *     path="/links",
     *     operationId="newLinkItem",
     *     tags={"Links"},
     *     summary="Add New Link",
     *     description="create new link",
     *     @OA\Parameter(
     *         name="Accept",
     *         description="Accept header to specify api version",
     *         required=false,
     *         in="header",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         request="text",
     *         required=true,
     *         description="The text of the task",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         request="is_completed",
     *         required=true,
     *         description="If the task is completed or not",
     *         @OA\Schema(
     *             type="boolean"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The task item created",
     *         @OA\JsonContent(ref="#/components/schemas/task-transformer")
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function store(ThisRequest $request) {
        $result = ThisModel::create(array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id,
        ]));

        if ( $result ) {
            $tags = $request->input('tags', []);
            $exist_tags = Tag::where('created_by', auth('admin')->user()->id)
                ->whereIn('type', ['commons', 'links'])
                ->whereIn('title', $tags)
                ->pluck('title', 'id')->toArray();
            $not_exist_tags = array_diff($tags, $exist_tags);

            if ( $not_exist_tags ) {
                $temp = [];
                foreach ( $not_exist_tags as $tag ) {
                    $temp[] = [
                        'title' => $tag,
                        'slug' => str_slug($tag),
                        'type' => 'commons',
                        'description' => $tag,
                        'created_by' => auth('admin')->user()->id,
                    ];
                }
                $create_result = Tag::insert($temp);
            }

            if ( $tags ) {
                $tags = Tag::where('created_by', auth('admin')->user()->id)
                    ->whereIn('type', ['commons', 'links'])
                    ->whereIn('title', $tags)
                    ->pluck('id')->toArray();
                $tags = array_combine($tags, array_fill(0, count($tags), ['table' => 'links']));
                $result->tags()->attach($tags);
            }
        }

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['link']); // 列表
        } else {
            flash('操作失败', 'error');

            return back(); // 继续
        }
    }
    /**
     * @OA\Get(
     *     path="/links/{id}",
     *     operationId="getLinkItem",
     *     tags={"Links"},
     *     summary="Get Link",
     *     description="Get specify link by id",
     *     @OA\Parameter(
     *         name="Accept",
     *         description="Accept header to specify api version",
     *         required=false,
     *         in="header",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         description="The id of the task",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The task item",
     *         @OA\JsonContent(ref="#/components/schemas/task-transformer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="404 not found"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function show(int $id) {}
    public function edit(int $id) {
        $types = auth('admin')->user()->profile->links;
        $types = json_decode($types, true);

        $category_array = Category::where('created_by', auth('admin')->user()->id)->where('type', 'commons')->get();
        $categories = level_array($category_array);
        $categories = plain_array($categories, 0, '==');

        $tags = Tag::where('created_by', auth('admin')->user()->id)->whereIn('type', ['commons'])->pluck('title');
        $tags = json_encode($tags);

        $item = ThisModel::with('tags')->find($id);

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('types', 'categories', 'tags', 'item')));
    }
    /**
     * @OA\Put(
     *     path="/links/{id}",
     *     operationId="updateLinkItem",
     *     tags={"Links"},
     *     summary="Update Link",
     *     description="update existed task by id",
     *     @OA\Parameter(
     *         name="Accept",
     *         description="Accept header to specify api version",
     *         required=false,
     *         in="header",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         description="The id of the task",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         request="task_in_body",
     *         required=true,
     *         description="The task to update",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/task-model"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The task item updated",
     *         @OA\JsonContent(ref="#/components/schemas/task-transformer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="404 not found"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function update(ThisRequest $request, int $id) {
        $item = ThisModel::find($id);
        $result = $item->update($request->all());

        if ( $result ) {
            $tags = $request->input('tags', []);
            $exist_tags = Tag::where('created_by', auth('admin')->user()->id)
                ->whereIn('type', ['commons', 'links'])
                ->whereIn('title', $tags)
                ->pluck('title', 'id')->toArray();
            $not_exist_tags = array_diff($tags, $exist_tags);

            if ( $not_exist_tags ) {
                $temp = [];
                foreach ( $not_exist_tags as $tag ) {
                    $temp[] = [
                        'title' => $tag,
                        'slug' => str_slug($tag),
                        'type' => 'commons',
                        'description' => $tag,
                        'created_by' => auth('admin')->user()->id,
                    ];
                }
                $create_result = Tag::insert($temp);
            }

            if ( $tags ) {
                $tags = Tag::where('created_by', auth('admin')->user()->id)
                    ->whereIn('type', ['commons', 'links'])
                    ->whereIn('title', $tags)
                    ->pluck('id')->toArray();
                $tags = array_combine($tags, array_fill(0, count($tags), ['table' => 'links']));
                $item->tags()->detach();
                $item->tags()->attach($tags);
            }
        }

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['link']); // 列表
        } else {
            flash('操作失败', 'error');

            return back(); // 继续
        }
    }
    /**
     * @OA\Delete(
     *     path="/links/{id}",
     *     operationId="deleteLinkItem",
     *     tags={"Links"},
     *     summary="Delete Link",
     *     description="delete existed link by id",
     *     @OA\Parameter(
     *         name="Accept",
     *         description="Accept header to specify api version",
     *         required=false,
     *         in="header",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         description="The id of the task",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The task is deleted successful"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="404 not found"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
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
