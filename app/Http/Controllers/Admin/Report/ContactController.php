<?php
namespace App\Http\Controllers\Admin\Report;

use App\Models\Report\Contact as ThisModel;
use App\Models\Architecture\Corporation;

use Illuminate\Http\Request;
use App\Http\Requests\Report\ContactRequest as ThisRequest;

class ContactController extends Controller { // 网电报表
    private $baseInfo = [
        'slug' => 'report_contacts',
        'title' => '网电报表',
        'description' => '网电报表',
        'link' => '/admin/report_contacts',
        'parent_title' => '报表',
        'parent_link' => '/admin/report_sems',
        'view_path' => 'admin.report.contact.',
    ];

    public function index(Request $request) {
        if ( $request->ajax() ) {
            $draw = $request->input('draw', 1);
            $start = $request->input('start', 0);
            $length = $request->input('length', config('beesoft.page.size'));

            $order['name'] = $request->input('columns.' .$request->input('order.0.column') . '.name', 'created_at'); // 排序字段
            $order['dir'] = $request->input('order.0.dir', 'desc'); // 升降序

            $columns = $request->input('columns');

            $search_date = $columns[0]['search']['value'];
            $search_last_date = $columns[3]['search']['value'];
            $search_corporation_id = $columns[1]['search']['value'];
            $search_statistics = $columns[4]['search']['value'];

            $model = ThisModel::where('created_by', auth('admin')->user()->id);
            $modeltotal = clone $model;
            $modeltotal = $modeltotal->selectRaw('count(1) as aggregate, sum(onduty) as onduty, sum(callback) as callback, sum(callback_real) as callback_real, sum(callback_old) as callback_old, sum(callback_old_real) as callback_old_real, sum(visit) as visit, sum(previsit) as previsit, sum(money) as money');
            
            if ( $search_statistics=="true" ) { // 统计
                $model = $model->selectRaw('corporation_id, count(1) as aggregate, sum(onduty) as onduty, sum(callback) as callback, sum(callback_real) as callback_real, sum(callback_old) as callback_old, sum(callback_old_real) as callback_old_real, sum(visit) as visit, sum(previsit) as previsit, sum(money) as money');
                $model = $model->groupBy(['corporation_id']);
            }
            if ( $search_date ) {
                if ( $search_last_date && ($search_date != $search_last_date) ) {
                    $modeltotal = $modeltotal->where('date', '>=', $search_date)->where('date', '<=', $search_last_date);
                    $model = $model->where('date', '>=', $search_date)->where('date', '<=', $search_last_date);
                } else {
                    $modeltotal = $modeltotal->where('date', $search_date);
                    $model = $model->where('date', $search_date);
                }
            }
            if ( $search_corporation_id ) {
                $modeltotal = $modeltotal->where('corporation_id', $search_corporation_id);
                $model = $model->where('corporation_id', $search_corporation_id);
            }
            if ( !in_array(auth('admin')->user()->id, [0, 1]) ) {
                $user_corporation_id = auth('admin')->user()->corporation_id;
                $modeltotal = $modeltotal->where('corporation_id', $user_corporation_id);
                $model = $model->where('corporation_id', $user_corporation_id);
            }

            $count = $model->count(); // 总数

            if ( $search_statistics=="true" ) {
                switch ( $order['name'] ) {
                    case 'callback_avg':
                    case 'callback_real_avg':
                    case 'callback_old_avg':
                    case 'callback_old_real_avg':
                        $model = $model->orderByRaw("sum(`".substr($order['name'], 0, -4)."`)/sum(`onduty`) {$order['dir']}");
                    break;
                    case 'callback_old_percent':
                        $model = $model->orderByRaw("sum(`callback_old`)/sum(`callback`) {$order['dir']}");
                    break;
                    case 'callback_old_real_percent':
                        $model = $model->orderByRaw("sum(`callback_old_real`)/sum(`callback_real`) {$order['dir']}");
                    break;
                    default:
                        $model = $model->orderBy($order['name'], $order['dir']);
                    break;
                }
            } else {
                switch ( $order['name'] ) {
                    case 'callback_avg':
                    case 'callback_real_avg':
                    case 'callback_old_avg':
                    case 'callback_old_real_avg':
                        $model = $model->orderByRaw("`".substr($order['name'], 0, -4)."`/`onduty` {$order['dir']}");
                    break;
                    case 'callback_old_percent':
                        $model = $model->orderByRaw("`callback_old`/`callback` {$order['dir']}");
                    break;
                    case 'callback_old_real_percent':
                        $model = $model->orderByRaw("`callback_old_real`/`callback_real` {$order['dir']}");
                    break;
                    default:
                        $model = $model->orderBy($order['name'], $order['dir']);
                    break;
                }
            }

            $model = $model->offset($start)->limit($length)->get();
            $modeltotal = $modeltotal->first()->toArray();

            $modeltotal['date'] = '';
            $modeltotal['corporation_title'] = '汇总';

            if ( $model ) {
                foreach ( $model as $item ) {
                    $item->corporation_title = $item->corporation ? $item->corporation->title : '汇总';

                    if ( $search_statistics=="true" && $search_date ) {
                        if ( $search_last_date ) {
                            $item->date = date("m-d", strtotime($search_date)) . "至" . date("m-d", strtotime($search_last_date));
                        } else {
                            $item->date = $search_date;
                        }
                    }
                }
            }

            return [
                'draw' => $draw,
                'recordsTotal' => $count,
                'recordsFiltered' => $count,
                'data' => array_merge($model->toArray(), [
                    $modeltotal
                ]),
            ];
        } else {
            $search = [
                'corporation_id' => 0,
                'statistics' => 0,
                'date' => date('Y-m-d', time()-86400),
                'last_date' => '',
            ];

            $corporations = Corporation::all('id', 'description')->toArray();

            return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('corporations', 'search')));
        }
    }
    public function create() {
        $corporations = Corporation::all('id', 'description')->toArray();

        return view($this->baseInfo['view_path'].'create', compact('corporations'));
    }
    public function store(Request $request) {
        $attr = $request->all();
        $user = auth('admin')->user();
        $attr['user_id'] = $user->id;
        if ( !isset($attr['hospital_id']) ) $attr['hospital_id'] = $user->hospital_id;

        if ( $attr['id'] ) {
            $res = $this->update($attr, $attr['id']);
        } else {
            $res = $model->create($attr);
        }

        if ( $res ) {
            flash('操作成功', 'success');
        } else {
            flash('操作失败', 'error');
        }
        return $res;

        return redirect('admin/report');
    }
    // public function show(Server $server, $id=0) {
    //     if ( $id ) {
    //         echo $id;
    //     } else {
    //         $validator = \Validator::make($request->all(), [
    //             'date' => 'required|date|before:tomorrow',
    //         ]);

    //         if ( $validator->fails() ) {
    //             return back()
    //                 ->withErrors($validator)
    //                 ->withInput();
    //         }

    //         $data = Report::where([
    //             'date' => $request->input('date'),
    //             'hospital_id' => auth('admin')->user()->hospital_id,
    //         ])->first();

    //         return $data;
    //     }
    // }
    public function edit(Server $server) {}
    public function update(Request $request, Server $server) {}
    public function destroy(Server $server) {}
}
