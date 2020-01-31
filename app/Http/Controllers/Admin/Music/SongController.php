<?php
namespace App\Http\Controllers\Admin\Music;

use App\Models\Music\Song as ThisModel;
use App\Models\Music\Singer;
use App\Models\Music\Album;

use Illuminate\Http\Request;
use App\Http\Requests\Music\SongRequest as ThisRequest;

class SongController extends Controller {
    private $baseInfo = [
        'slug' => 'songs',
        'title' => '歌曲',
        'description' => '歌曲列表',
        'link' => '/admin/songs',
        'parent_title' => '音乐',
        'parent_link' => '/admin/songs',
        'view_path' => 'admin.music.song.',
    ];
    private $show = [
        'id',
        'title',
        'created_at',
        'updated_at',
    ];

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
                    $model = $model->where('title', 'like', "%{$search['value']}%")
                        ->orWhere('description', 'like', "%{$search['value']}%");
                } else { // 完全匹配
                    $model = $model->where('title', $search['value'])
                        ->orWhere('description', $search['value']);
                }
            }

            $count = $model->count(); // 总数
            $model = $model->orderBy($order['name'], $order['dir']); // 排序
            $model = $model->offset($start)->limit($length)->get(); // 分页

            if ( $model ) {
                foreach ( $model as $item ) {
                    $item->singer_titles = implode("<br>\n", ($item->singers->pluck('title')->toArray()?:[]));
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
    public function create(Request $request) {
        $singers = Singer::pluck('title');
        $singers = json_encode($singers);

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('singers')));
    }
    public function store(ThisRequest $request) {
        $data = array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id,
        ]);
        $result = ThisModel::create($data);

        if ( $result ) {
            $singers = $request->input('singers', []);
            $exist_singers = Singer::whereIn('title', $singers)
                ->pluck('title', 'id')->toArray();
            $not_exist_singers = array_diff($singers, $exist_singers);

            if ( $not_exist_singers ) {
                $temp = [];
                foreach ( $not_exist_singers as $singer ) {
                    $temp[] = [
                        'title' => $singer,
                        'created_by' => auth('admin')->user()->id,
                    ];
                }
                $create_result = Singer::insert($temp);
            }

            if ( $singers ) {
                $singers = Singer::whereIn('title', $singers)
                    ->pluck('id')->toArray();
                $result->singers()->attach($singers);
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
    public function show(int $id) {}
    public function edit(int $id) {
        $singers = Singer::pluck('title');
        $singers = json_encode($singers);

        $item = ThisModel::with('singers')->find($id);

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('singers', 'item')));
    }
    public function update(ThisRequest $request, int $id) {
        $item = ThisModel::find($id);
        $data = $request->all();
        $result = $item->update($data);

        if ( $result ) {
            $singers = $request->input('singers', []);
            $exist_singers = Singer::whereIn('title', $singers)
                ->pluck('title', 'id')->toArray();
            $not_exist_singers = array_diff($singers, $exist_singers);

            if ( $not_exist_singers ) {
                $temp = [];
                foreach ( $not_exist_singers as $singer ) {
                    $temp[] = [
                        'title' => $singer,
                        'created_by' => auth('admin')->user()->id,
                    ];
                }
                $create_result = Singer::insert($temp);
            }

            if ( $singers ) {
                $singers = Singer::whereIn('title', $singers)
                    ->pluck('id')->toArray();
                $item->singers()->detach();
                $item->singers()->attach($singers);
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
