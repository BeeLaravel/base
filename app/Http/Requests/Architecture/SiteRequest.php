<?php
namespace App\Http\Requests\Architecture;

use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest {
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
