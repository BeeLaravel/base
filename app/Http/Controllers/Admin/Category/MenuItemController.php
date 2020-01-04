<?php
namespace App\Http\Controllers\Admin\Category;

use App\Models\Category\MenuItem as ThisModel;

use Illuminate\Http\Request;
use App\Http\Requests\Category\MenuItemRequest;

class MenuItemController extends Controller {
    private $baseInfo = [
        'slug' => 'menu_items',
        'title' => '菜单项',
        'description' => '菜单项列表',
        'link' => '/admin/menu_items',
        'index_link' => '/admin/menus',
        'parent_title' => '分类',
        'parent_link' => '/admin/menus',
        'view_path' => 'admin.category.menu_item.',
    ];
    private $show = [
        'id',
        'title',
        'slug',
        'sort',
        'created_at',
        'updated_at',
    ];

    public function index($menu_id, Request $request) {
        $menu_item_array = ThisModel::whereMenuId($menu_id)->get();
        $this->sortList($menu_item_array, 0, $menu_items);

        return view($this->baseInfo['view_path'].'index', array_merge($this->baseInfo, compact('menu_items', 'menu_id')));
    }
    protected function sortList($data, $id=0, &$arr=[]) {
        foreach ( $data as $item ) {
            if ( $id == $item->parent_id ) {
                $item->button = $item->getActionButtons('menu_items');

                $arr[] = $item->toArray();
                $this->sortList($data, $item->id, $arr);
            }
        }

        return $arr;
    }
    public function create($menu_id, Request $request) {
        $menu_item_array = ThisModel::whereMenuId($menu_id)->get();
        $menu_items = level_array($menu_item_array);
        $menu_items = plain_array($menu_items, 0, '==');

        return view($this->baseInfo['view_path'].'create', array_merge($this->baseInfo, compact('menu_items', 'menu_id')));
    }
    public function store(MenuItemRequest $request) {
        $result = ThisModel::create(array_merge($request->all(), [
            'created_by' => auth('admin')->user()->id
        ]));

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['index_link'].'/'.$result->menu_id);
        } else {
            flash('操作失败', 'error');

            return back();
        }
    }
    public function show(int $id) {}
    public function edit(int $id) {
        $item = ThisModel::find($id);

        $menu_item_array = ThisModel::whereMenuId($item->menu_id)->get();
        $menu_items = level_array($menu_item_array);
        $menu_items = plain_array($menu_items, 0, '==');

        return view($this->baseInfo['view_path'].'edit', array_merge($this->baseInfo, compact('menu_items', 'item')));
    }
    public function update(MenuItemRequest $request, int $id) {
        $menu = ThisModel::find($id);
        $result = $menu->update($request->all());

        if ( $result ) {
            flash('操作成功', 'success');

            return redirect($this->baseInfo['index_link'].'/'.$menu->menu_id);
        } else {
            flash('操作失败', 'error');

            return back();
        }
    }
    public function destroy(Request $request, int $id) {
        $item = ThisModel::find($id);
        $result = ThisModel::destroy($id);

        if ( $result ) {
            flash('删除成功', 'success');
        } else {
            flash('删除失败', 'error');
        }

        return redirect($this->baseInfo['index_link'].'/'.$item->menu_id);
    }
}
