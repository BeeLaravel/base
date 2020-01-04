<?php
namespace App\Http\Requests\Workflow;

use Illuminate\Foundation\Http\FormRequest;

class PlaceRequest extends FormRequest {
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
