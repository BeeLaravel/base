<?php
namespace App\Http\Controllers\Admin\Report;

use App\Models\Report\Introduce as ThisModel;
use App\Models\Architecture\Corporation;

use Illuminate\Http\Request;
use App\Http\Requests\Report\IntroduceRequest as ThisRequest;

class IntroduceController extends Controller { // 老带新报表
    private $baseInfo = [
        'slug' => 'report_introduces',
        'title' => '老带新报表',
        'description' => '老带新报表',
        'link' => '/admin/report_introduces',
        'parent_title' => '报表',
        'parent_link' => '/admin/report_sems',
        'view_path' => 'admin.report.introduce.',
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
            $modeltotal = $modeltotal->selectRaw('count(1) as aggregate, sum(achievement) as achievement, sum(achievement_first) as achievement_first, sum(bespeak) as bespeak, sum(visit) as visit');
            
            if ( $search_statistics=="true" ) { // 统计
                $model = $model->selectRaw('corporation_id, count(1) as aggregate, sum(achievement) as achievement, sum(achievement_first) as achievement_first, sum(bespeak) as bespeak, sum(visit) as visit');
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
                    case 'visit_percent': // 到诊率
                        $model = $model->orderByRaw("if (sum(`bespeak`), sum(`visit`/`bespeak`), 0) {$order['dir']}");
                    break;
                    case 'first_visit_price': // 初诊客单价
                        $model = $model->orderByRaw("sum(`achievement_first`)/sum(`visit`) {$order['dir']}");
                    break;
                    case 'achievement_other':
                        $model = $model->orderByRaw("sum(`achievement`)-sum(`achievement_first`) {$order['dir']}");
                    break;
                    default:
                        $model = $model->orderBy($order['name'], $order['dir']);
                    break;
                }
            } else {
                switch ( $order['name'] ) {
                    case 'visit_percent': // 到诊率
                        $model = $model->orderByRaw("if (`bespeak`, `visit`/(`bespeak`), 0) {$order['dir']}");
                    break;
                    case 'first_visit_price': // 初诊客单价
                        $model = $model->orderByRaw("`achievement_first`/`visit` {$order['dir']}");
                    break;
                    case 'achievement_other':
                        $model = $model->orderByRaw("`achievement`-`achievement_first` {$order['dir']}");
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
