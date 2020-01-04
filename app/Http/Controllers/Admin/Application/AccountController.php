<?php
namespace App\Http\Controllers\Admin\Application;

use App\Models\Application\Account as ThisModel;
use App\Models\Category\Category;
use App\Models\Category\Tag;

use Illuminate\Http\Request;
use App\Http\Requests\Application\AccountRequest as ThisRequest;

/**
 * @SWG\Definition(
 *     definition="Person",
 *     type="object",
 *     required={"username"},
 *     @SWG\Property(
 *         name="firstName",
 *         type="string"
 *     ),
 *     @SWG\Property(
 *         name="lastName",
 *         type="string"
 *     ),
 *     @SWG\Property(
 *         name="username",
 *         type="string"
 *     )
 * )
 */
/**
 * @SWG\Definition(
 *     definition="Error",
 *     @SWG\Property(
 *         property="code",
 *         type="string"
 *     ),
 *     @SWG\Property(
 *         property="message",
 *         type="string"
 *     )
 * )
 */

/**
 * @SWG\Response(
 *     response="Standard500ErrorResponse",
 *     description="An unexpected error occured.",
 *     @SWG\schema(ref="#/definitions/Error")
 * )
 */
/**
 * @SWG\Property(
 *     property="username",
 *     type="string",
 *     pattern="[a-z0-9]{8,64}",
 *     minLength="8",
 *     maxLength="64"
 * )
 * @SWG\Property(
 *     property="dateOfBirth",
 *     type="string",
 *     format="date"
 * ),
 * @SWG\Property(
 *     property="lastTimeOnline",
 *     type="string",
 *     format="dateTime"
 * )
 * @SWG\Parameter(
 *     name="pageSize",
 *     in="query"
 *     description="Number of persons returned",
 *     type="integer",
 *     format="int32",
 *     minimum: 0,
 *     exclusiveMinimum: true,
 *     maximum: 100,
 *     exclusiveMaximum: false,
 *     multipleOf: 10
 * )
 * @SWG\Property(
 *     property="code",
 *     type="string",
 *     enum={"DBERR", "NTERR", "UNERR"}
 * )
 * @SWG\Property(
 *     property="Persons",
 *     @SWG\Items(
 *         type="array",
 *         minItems="10",
 *         maxItems="100",
 *         uniqueItems="true",
 *         ref="#/definitions/Person"
 *     )
 * )
 * @SWG\Property(
 *     property="avatarBase64PNG",
 *     type="string",
 *     format="byte"
 * )
 */
class AccountController extends Controller {
    private $baseInfo = [
        'slug' => 'accounts',
        'title' => '账号',
        'description' => '账号列表',
        'link' => '/admin/accounts',
        'parent_title' => '应用',
        'parent_link' => '/admin/posts',
        'view_path' => 'admin.application.account.',
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
     * @SWG\Response(
     *     response="200",
     *     description="A list of Person",
     *     @SWG\schema(ref="#/definitions/Persons")
     * ),
     * @SWG\Response(
     *     response="500",
     *     ref="#/responses/Standard500ErrorResponse"
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
            $types = auth('admin')->user()->profile->accounts ?? '[]';
            $types = json_decode($types, true);

            $search = [
                'type' => $request->cookie('type') ?: '',
            ];

            return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('types', 'search')));
        }
    }
    public function create(Request $request) {
        $types = auth('admin')->user()->profile->accounts;
        $types = json_decode($types, true);

        $category_array = Category::where('created_by', auth('admin')->user()->id)->where('type', 'commons')->get();
        $categories = level_array($category_array);
        $categories = plain_array($categories, 0, '==');

        $tags = Tag::where('created_by', auth('admin')->user()->id)->whereIn('type', ['commons', 'links'])->pluck('title');
        $tags = json_encode($tags);

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('types', 'categories', 'tags')));
    }
    /**
     * @SWG\Post(
     *     path="/persons",
     *     summary="Creates a person",
     *     description="Adds a new person to the persons list.",
     *     @SWG\Parameter(
     *          name="person",
     *          in="body",
     *          required="true"
     *          description="The person to create.",
     *          @SWG\Schema(ref="#/definitions/Person")
     *     )
     * )
     */
    public function store(ThisRequest $request) {
        $data = array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id,
        ]);
        // if ( isset($data['password']) && $data['password'] ) {
            // $method = "DES-ECB"; // DES-ECB|AES-128-CBC
            // $password = "beesoft";
            // $data['password'] = openssl_encrypt($data['password'], $method, $password);
        // }
        $result = ThisModel::create($data);

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
     * @SWG\Response(
     *     response=200,
     *     description="A Person",
     *     @SWG\Schema(ref="#/definitions/Person")
     * )
     */
    public function show(int $id) {}
    public function edit(int $id) {
        $types = auth('admin')->user()->profile->accounts;
        $types = json_decode($types, true);

        $category_array = Category::where('created_by', auth('admin')->user()->id)->where('type', 'commons')->get();
        $categories = level_array($category_array);
        $categories = plain_array($categories, 0, '==');

        $tags = Tag::where('created_by', auth('admin')->user()->id)->whereIn('type', ['commons'])->pluck('title');
        $tags = json_encode($tags);

        $item = ThisModel::with('tags')->find($id);

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('types', 'categories', 'tags', 'item')));
    }
    public function update(ThisRequest $request, int $id) {
        $item = ThisModel::find($id);
        $data = $request->all();
        // if ( isset($data['password']) && $data['password'] ) {
            // $method = "DES-ECB"; // DES-ECB|AES-128-CBC
            // $password = "beesoft";
            // $data['password'] = openssl_encrypt($data['password'], $method, $password);
        // }
        $result = $item->update($data);

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
