<?php
namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class PictureRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        $data = [
            'title' => 'required',
            'sort' => 'bail|integer|min:0|max:255',
        ];

        $actions = explode('.', $this->route()->action['as']);
        if ( end($actions) == 'store' ) $data['image'] = 'required';

        return $data;
    }
    public function messages() {
        return [];
    }
}
