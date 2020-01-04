<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use App\Models\Category\Menu;

class AdminMenuComposer {
    protected $menu;

    public function compose(View $view) {
    	$menu_array = Menu::find(1)->items()->get()->toArray();
        $view->with('menus', $this->sortTreeList($menu_array));
    }
    public function sortTreeList($menu_array=[]) {
        $tree = $temp = [];

        foreach ( $menu_array as $menu ) {
            $temp[$menu['id']] = $menu;
        }

        foreach ( $menu_array as $menu ) {
            if ( isset($temp[$menu['parent_id']]) ) {
                $temp[$menu['parent_id']]['children'][] = &$temp[$menu['id']];
            } else {
                $tree[] = &$temp[$menu['id']];
            }
        }

        unset($temp);
        return $tree;
    }
}
