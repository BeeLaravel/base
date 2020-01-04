<?php
namespace App\Http\Requests\RBAC;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'slug' => 'required',
            'title' => 'required',
            'sort' => 'bail|integer|min:0|max:255',
        ];
    }
    public function messages() {
        return [];
    }
}
