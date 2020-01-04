<?php
namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'title' => 'required',
            'slug' => 'required',
            'content' => 'required',
            'sort' => 'bail|integer|min:0|max:255',
        ];
    }
    public function messages() {
        return [];
    }
}
