<?php
namespace App\Http\Controllers\Admin\RBAC;

use App\Models\RBAC\User as ThisModel;
use App\Models\Architecture\Corporation;
use App\Models\Architecture\Site;
use App\Models\Architecture\Department;
use App\Models\RBAC\Role;
use App\Models\Architecture\UserSite;
use App\Models\Architecture\UserDepartment;
use App\Models\RBAC\UserRole;

use Illuminate\Http\Request;
use App\Http\Requests\RBAC\UserRequest as ThisRequest;

use Hash;

class UserController extends Controller {
    private $baseInfo = [
        'slug' => 'users',
        'title' => '用户',
        'description' => '用户列表',
        'link' => '/admin/users',
        'parent_title' => 'RBAC',
        'parent_link' => '/admin/users',
        'view_path' => 'admin.rbac.user.',
    ];
    private $show = [
        'id',
        'email',
        'phone',
        'name',
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
                        ->orWhere('email', 'like', "%{$search['value']}%")
                        ->orWhere('phone', 'like', "%{$search['value']}%")
                        ->orWhere('name', 'like', "%{$search['value']}%")
                        ->orWhere('description', 'like', "%{$search['value']}%");
                } else { // 完全匹配
                    $model = $model->where('slug', $search['value'])
                        ->orWhere('email', $search['value'])
                        ->orWhere('phone', $search['value'])
                        ->orWhere('name', $search['value'])
                        ->orWhere('description', $search['value']);
                }
            }

            $count = $model->count(); // 总数
            $model = $model->orderBy($order['name'], $order['dir']); // 排序
            $model = $model->offset($start)->limit($length)->get(); // 分页

            if ( $model ) {
                foreach ( $model as $item ) {
                    $item->corporation_title = $item->corporation_id ? $item->corporation->title : '(未设置)';

                    $sites = [];
                    foreach ( $item->sites as $site ) {
                        $sites[] = $site->title;
                    }
                    $item->site_titles = $sites ? implode(';', $sites) : '(未设置站点)';

                    $departments = [];
                    foreach ( $item->departments as $department ) {
                        $departments[] = $department->title;
                    }
                    $item->department_titles = $departments ? implode(';', $departments) : '(未设置部门)';

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
        $corporation_array = Corporation::where('created_by', auth('admin')->user()->id)->get();
        $corporations = level_array($corporation_array);
        $corporations = plain_array($corporations, 0, '==');

        $sites = Site::get();
        $departments = Department::get();
        $roles = Role::get();

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('corporations', 'sites', 'departments', 'roles')));
    }
    public function store(ThisRequest $request) {
        $result = ThisModel::create(array_merge($request->all(), [
            'password' => Hash::make($request->input('password')),
            'created_by' => auth('admin')->user()->id,
        ]));

        if ( $result ) {
            $roles = $request->input('roles', []);
            $user_roles = [];
            if ( $roles ) {
                foreach ( $roles as $role ) {
                    $user_roles[] = [
                        'user_id' => $result->id,
                        'role_id' => $role,
                    ];
                }
            }

            UserRole::insert($user_roles);
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

        $item['sites'] = UserSite::where('user_id', $id)->pluck('site_id')->toArray();
        $item['departments'] = UserDepartment::where('user_id', $id)->pluck('department_id')->toArray();
        $item['roles'] = UserRole::where('user_id', $id)->pluck('role_id')->toArray();

        $corporation_array = Corporation::where('created_by', auth('admin')->user()->id)->get();
        $corporations = level_array($corporation_array);
        $corporations = plain_array($corporations, 0, '==');

        $sites = Site::get();
        $departments = Department::get();
        $roles = Role::get();

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('item', 'corporations', 'sites', 'departments', 'roles')));
    }
    public function update(ThisRequest $request, int $id) {
        $item = ThisModel::find($id);

        $request_data = $request->all();

        if ( $request_data['password'] ) {
            $request_data['password'] = Hash::make($request_data['password']);
        } else {
            unset($request_data['password']);
        }

        $result = $item->update($request_data);

        if ( $result ) {
            $sites = $request->input('sites', []);
            $user_sites = [];
            if ( $sites ) {
                foreach ( $sites as $site ) {
                    $user_sites[] = [
                        'user_id' => $id,
                        'site_id' => $site,
                    ];
                }
            }
            
            UserSite::where('user_id', $id)->delete();
            UserSite::insert($user_sites);

            $departments = $request->input('departments', []);
            $user_departments = [];
            if ( $departments ) {
                foreach ( $departments as $department ) {
                    $user_departments[] = [
                        'user_id' => $id,
                        'department_id' => $department,
                    ];
                }
            }
            
            UserDepartment::where('user_id', $id)->delete();
            UserDepartment::insert($user_departments);

            $roles = $request->input('roles', []);
            $user_roles = [];
            if ( $roles ) {
                foreach ( $roles as $role ) {
                    $user_roles[] = [
                        'user_id' => $id,
                        'role_id' => $role,
                    ];
                }
            }
            
            UserRole::where('user_id', $id)->delete();
            UserRole::insert($user_roles);
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
