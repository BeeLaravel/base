<?php
namespace App\Http\Controllers\Admin\Configure;

use App\Models\Configure\ConfigureItem as ThisModel;

use Illuminate\Http\Request;
use App\Http\Requests\Configure\ConfigureItemRequest as ThisRequest;

class ConfigureItemController extends Controller {
    private $baseInfo = [
        'slug' => 'configure-items',
        'title' => '配置项',
        'description' => '配置项列表',
        'link' => '/admin/configure-items',
        'parent_title' => '配置',
        'parent_link' => '/admin/configures',
        'parent_category' => 'configure',
        'view_path' => 'admin.configure.configure-item.',
        'api_list' => 'configure-item',
    ];
    private $show = [
        'id',
        'title',
        'slug',
        // 'configure_id',
        'created_at',
        'updated_at',
    ];

    public function index($configure_id, Request $request) {
        if ( $request->ajax() ) {
            $draw = $request->input('draw', 1);
            $start = $request->input('start', 0);
            $length = $request->input('length', 20);

            $order['name'] = $request->input('columns.' .$request->input('order.0.column') . '.name', 'id'); // 排序字段
            $order['dir'] = $request->input('order.0.dir', 'desc'); // 升降序
            $search['value'] = $request->input('search.value', ''); // 搜索字段
            $search['regex'] = $request->input('search.regex', false); // 是否正则

            $model = ThisModel::where('deleted_at'); // where('created_by', auth('admin')->user()->id)
            if ( $configure_id ) $model = $model->where('configure_id', $configure_id);

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
            // \DB::connection()->enableQueryLog();
            $model = $model->offset($start)->limit($length)->get(); // 分页
            // $logs = \DB::getQueryLog();
            // dd($logs);

            if ( $model ) {
                foreach ( $model as $item ) {
                    $item->configure_title = $item->configure->title;
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

            return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('search', 'configure_id')));
        }
    }
    public function create(Request $request) {
        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, []));
    }
    public function store(ThisRequest $request) {
        $result = ThisModel::create(array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id,
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

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('item')));
    }
    public function update(ThisRequest $request, int $id) {
        $item = ThisModel::find($id);
        $result = $item->update($request->all());

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
