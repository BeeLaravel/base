<?php
namespace App\Http\Controllers\Admin\RBAC;

use App\Models\RBAC\Role as ThisModel;
use App\Models\RBAC\Permission;
use App\Models\RBAC\RolePermission;

use Illuminate\Http\Request;
use App\Http\Requests\RBAC\RoleRequest as ThisRequest;

class RoleController extends Controller {
    private $baseInfo = [
        'slug' => 'roles',
        'title' => '角色',
        'description' => '角色列表',
        'link' => '/admin/roles',
        'parent_title' => 'RBAC',
        'parent_link' => '/admin/users',
        'view_path' => 'admin.rbac.role.',
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
        $permission_array = Permission::get();
        $permissions = level_array($permission_array);

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('permissions')));
    }
    public function store(ThisRequest $request) {
        $result = ThisModel::create(array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id,
        ]));

        if ( $result ) {
            $permissions = $request->input('permissions', []);
            $role_permissions = [];
            foreach ( $permissions as $permission ) {
                $role_permissions[] = [
                    'role_id' => $result->id,
                    'permission_id' => $permission,
                ];
            }

            RolePermission::insert($role_permissions);
        }

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

        $item['permissions'] = RolePermission::where('role_id', $id)->pluck('permission_id')->toArray();

        $permission_array = Permission::get();
        $permissions = level_array($permission_array);

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('item', 'permissions')));
    }
    public function update(ThisRequest $request, int $id) {
        $item = ThisModel::find($id);
        $result = $item->update($request->all());

        if ( $result ) {
            $permissions = $request->input('permissions', []);
            $role_permissions = [];
            foreach ( $permissions as $permission ) {
                $role_permissions[] = [
                    'role_id' => $id,
                    'permission_id' => $permission,
                ];
            }
            
            RolePermission::where('role_id', $id)->delete();
            RolePermission::insert($role_permissions);
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
            RolePermission::where('role_id', $id)->delete();
        }

        if ( $result ) {
            flash('删除成功', 'success');
        } else {
            flash('删除失败', 'error');
        }

        return redirect($this->baseInfo['link']);
    }
}
