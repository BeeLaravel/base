<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/categories';
    protected $logoutRedirectTo = '/admin/login';

    public function __construct() {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm() {
        $style = [
            'body-class' => 'bg-white',
        ];
        return view('admin.auth.login_v3', compact('style'));
    }

    public function logout(Request $request) {
        $this->guard('admin')->logout();

        // $request->session()->invalidate();

        return redirect($this->logoutRedirectTo);
    }
    protected function guard() {
        return Auth::guard('admin');
    }
}
