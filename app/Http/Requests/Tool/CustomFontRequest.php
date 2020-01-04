<?php
namespace App\Http\Requests\Tool;

use Illuminate\Foundation\Http\FormRequest;

class CustomFontRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'title' => 'required|max:50',
            'content' => 'required',
        ];
    }
}
