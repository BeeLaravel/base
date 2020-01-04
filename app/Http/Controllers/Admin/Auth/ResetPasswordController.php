<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller {
    use ResetsPasswords;

    protected $redirectTo = '/admin';

    public function __construct() {
        $this->middleware('guest:admin');
    }
}
