<?php
namespace App\Http\Controllers\Admin\Architecture;

use App\Models\Architecture\Corporation as ThisModel;

use Illuminate\Http\Request;
use App\Http\Requests\Architecture\CorporationRequest as ThisRequest;

class CorporationController extends Controller {
    private $baseInfo = [
        'slug' => 'corporations',
        'title' => '公司',
        'description' => '公司列表',
        'link' => '/admin/corporations',
        'parent_title' => '架构',
        'parent_link' => '/admin/departments',
        'view_path' => 'admin.architecture.corporation.',
    ];
    private $show = [
        'id',
        'slug',
        'title',
        'description',
        'created_at',
        'updated_at',
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
                        default:
                            if ( $value['search']['regex']=='true' ) { // 正则
                                $model = $model->where($this->show[$key], 'like', "%{$value['search']['value']}%");
                            } else { // 普通
                                $model = $model->where($this->show[$key], $value['search']['value']);
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
                    $item->parent_title = $item->parent_id ? $item->parent->title : '(未设置)';
                    $item->creater_name = $item->created_by ? $item->creater->name : '(未设置)';
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
            $search = [
            ];

            return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('search')));
        }
    }
    public function create() {
        $parent_array = ThisModel::where('created_by', auth('admin')->user()->id)->get();
        $parents = level_array($parent_array);
        $parents = plain_array($parents, 0, '==');

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('parents')));
    }
    public function store(ThisRequest $request) {
        $result = ThisModel::create(array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id,
        ]));

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['link']); // 列表
        } else {
            flash('操作失败', 'error');

            return back(); // 继续
        }
    }
    public function show(int $id) {}
    public function edit(int $id) {
        $item = ThisModel::find($id);

        $parent_array = ThisModel::where('created_by', auth('admin')->user()->id)->get();
        $parents = level_array($parent_array);
        $parents = plain_array($parents, 0, '==');

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('item', 'parents')));
    }
    public function update(ThisRequest $request, int $id) {
        $item = ThisModel::find($id);
        $result = $item->update($request->all());

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
