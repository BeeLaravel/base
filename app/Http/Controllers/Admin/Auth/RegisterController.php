<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\RBAC\User;

class RegisterController extends Controller {
    use RegistersUsers;

    protected $redirectTo = '/admin/categories';

    public function __construct() {
        $this->middleware('guest:admin');
    }

    protected function validator(array $data) { // 验证
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function create(array $data) { // 创建模型
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function showRegistrationForm() { // 注册表单
        $style = [
            'body-class' => 'bg-white',
        ];
        return view('admin.auth.register_v3', compact('style'));
    }
}
