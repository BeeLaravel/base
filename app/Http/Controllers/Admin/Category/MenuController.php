<?php
namespace App\Http\Controllers\Admin\Category;

use App\Models\Category\Menu as ThisModel;

use Illuminate\Http\Request;
use App\Http\Requests\Category\MenuRequest;

class MenuController extends Controller {
    private $baseInfo = [
        'slug' => 'menus',
        'title' => '菜单',
        'description' => '菜单列表',
        'link' => '/admin/menus',
        'parent_title' => '分类',
        'parent_link' => '/admin/menus',
        'view_path' => 'admin.category.menu.',
    ];
    private $show = [
        'id',
        'title',
        'slug',
        'sort',
        'created_at',
        'updated_at',
    ];

    public function index(Request $request) {
        if ( $request->ajax() ) {
            $draw = $request->input('draw', 1); // 页面次序
            $start = $request->input('start', 0); // 开始记录
            $length = $request->input('length', 20); // 分页长度

            $order['name'] = $request->input('columns.' .$request->input('order.0.column') . '.name', 'id'); // 排序字段
            $order['dir'] = $request->input('order.0.dir', 'desc'); // 升降序
            $search['value'] = $request->input('search.value', ''); // 搜索字段
            $search['regex'] = $request->input('search.regex', false); // 是否正则

            $model = new ThisModel;
            // ::where('created_by', auth('admin')->user()->id); // 筛选用户

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
                    $item->title = "<a href='".url($this->baseInfo['link'].'/'.$item->id)."'>".$item->title."</a>";
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
            $types = auth('admin')->user()->profile->menus ?? '[]';
            $types = json_decode($types, true);

            $parent_id = $request->input('parent_id', 0);

            $search = [
                'parent_id' => $parent_id,
            ];

            $parent = null;
            if ( $parent_id ) $parent = ThisModel::find($parent_id);

            return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('types', 'search', 'parent')));
        }
    }
    public function create(Request $request) {
        return view($this->baseInfo['view_path'].'create', $this->baseInfo);
    }
    public function store(MenuRequest $request) {
        $result = ThisModel::create(array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id
        ]));

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['link']);
        } else {
            flash('操作失败', 'error');

            return back();
        }
    }
    public function show(int $id) {}
    public function edit(int $id) {
        $item = ThisModel::find($id);

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('menus', 'item')));
    }
    public function update(MenuRequest $request, int $id) {
        $menu = ThisModel::find($id);
        $result = $menu->update($request->all());

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['link']);
        } else {
            flash('操作失败', 'error');

            return back();
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
