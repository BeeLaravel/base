<?php
namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'title' => 'required',
            'url' => 'required',
            'sort' => 'bail|integer|min:0|max:255',
        ];
    }
    public function messages() {
        return [];
    }
}
