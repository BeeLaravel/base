<?php
namespace App\Http\Requests\Tool;

use Illuminate\Foundation\Http\FormRequest;

class SvgRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'title' => 'required|max:50',
            'url' => 'required',
        ];
    }
}
