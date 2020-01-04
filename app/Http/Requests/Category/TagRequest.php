<?php
namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'title' => 'required',
            'slug' => 'required',
        ];
    }
    public function messages() {
        return [];
    }
}
