<?php
namespace App\Http\Controllers\Admin\Report;

use App\Models\Report\Sem as ThisModel;
use App\Models\Architecture\Corporation;

use Illuminate\Http\Request;
use App\Http\Requests\Report\SemRequest as ThisRequest;

class SemController extends Controller { // 竞价报表
    private $baseInfo = [
        'slug' => 'report_sems',
        'title' => '竞价报表',
        'description' => '竞价报表',
        'link' => '/admin/report_sems',
        'parent_title' => '报表',
        'parent_link' => '/admin/report_sems',
        'view_path' => 'admin.report.sem.',
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
            $modeltotal = $modeltotal->selectRaw('count(1) as aggregate, sum(consumption) as consumption, sum(consumption_real) as consumption_real, sum(dialog_useful) as dialog_useful, sum(dialog_useless) as dialog_useless, sum(bespeak) as bespeak, sum(visit) as visit');
            
            if ( $search_statistics=="true" ) { // 统计
                $model = $model->selectRaw('corporation_id, "汇总" as date, count(1) as aggregate, sum(consumption) as consumption, sum(consumption_real) as consumption_real, sum(dialog_useful) as dialog_useful, sum(dialog_useless) as dialog_useless, sum(bespeak) as bespeak, sum(visit) as visit');
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
                switch ( $order['name'] ) { // 排序
                    case 'dialog_useful_cost':
                    case 'bespeak_cost':
                    case 'visit_cost':
                        $model = $model->orderByRaw("sum(`consumption_real`)/sum(`".substr($order['name'], 0, -5)."`) {$order['dir']}");
                    break;
                    case 'dialog_useless_percent':
                        $model = $model->orderByRaw("if (sum(`dialog_useless`), sum(`dialog_useless`)/(sum(`dialog_useful`)+sum(`dialog_useless`)), 0) {$order['dir']}");
                    break;
                    case 'bespeak_percent':
                        $model = $model->orderByRaw("(sum(`bespeak`)/sum(`dialog_useful`)) {$order['dir']}");
                    break;
                    case 'visit_percent':
                        $model = $model->orderByRaw("(sum(`visit`)/sum(`bespeak`)) {$order['dir']}");
                    break;
                    default:
                        $model = $model->orderBy($order['name'], $order['dir']);
                    break;
                }
            } else {
                switch ( $order['name'] ) { // 排序
                    case 'dialog_useful_cost':
                    case 'bespeak_cost':
                    case 'visit_cost':
                        $model = $model->orderByRaw("`consumption_real`/`".substr($order['name'], 0, -5)."` {$order['dir']}");
                    break;
                    case 'dialog_useless_percent':
                        $model = $model->orderByRaw("if (`dialog_useless`, `dialog_useless`/(`dialog_useful`+`dialog_useless`), 0) {$order['dir']}");
                    break;
                    case 'bespeak_percent':
                        $model = $model->orderByRaw("`bespeak`/`dialog_useful` {$order['dir']}");
                    break;
                    case 'visit_percent':
                        $model = $model->orderByRaw("`visit`/`bespeak` {$order['dir']}");
                    break;
                    default:
                        $model = $model->orderBy($order['name'], $order['dir']);
                    break;
                }
            }

            $model = $model->offset($start)->limit($length)->get();
            $modeltotal = $modeltotal->first()->toArray();

            if ( $modeltotal ) {
                if ( $modeltotal['dialog_useful'] ) $modeltotal['dialog_useful_cost'] = round($modeltotal['consumption_real'] / $modeltotal['dialog_useful'], 2);
                if ( $modeltotal['bespeak'] ) $modeltotal['bespeak_cost'] = round($modeltotal['consumption_real'] / $modeltotal['bespeak'], 2);
                if ( $modeltotal['visit'] ) $modeltotal['visit_cost'] = round($modeltotal['consumption_real'] / $modeltotal['visit'], 2);
            } else {
                $modeltotal = [
                    'dialog_useful_cost' => 0,
                    'bespeak_cost' => 0,
                    'visit_cost' => 0,
                    'dialog_useful' => 0,
                    'bespeak' => 0,
                    'consumption' => 0,
                    'consumption_real' => 0,
                    'visit' => 0,
                    'dialog_useless' => 0,
                    'dialog_useless_percent' => 0,
                    'bespeak_percent' => 0,
                    'visit_percent' => 0,
                ];
            }

            $modeltotal['date'] = '';
            $modeltotal['corporation_title'] = '汇总';

            if ( $model ) {
                foreach ( $model as $item ) {
                    $item->corporation_title = $item->corporation ? $item->corporation->title : '汇总';
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
