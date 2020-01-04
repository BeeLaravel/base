<?php
namespace App\Http\ViewComposers\Backend;

use Illuminate\Contracts\View\View;
use App\Models\User\Menu;

class ApplicationMenuComposer {
    public function compose(View $view) {
        $view->with('application_menus', Menu::find(2)->items()->get());
    }
}
