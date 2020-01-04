<?php
namespace App\Http\Requests\RBAC;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'phone' => 'required',
            'email' => 'required',
            'name' => 'required',
        ];
    }
    public function messages() {
        return [];
    }
}
