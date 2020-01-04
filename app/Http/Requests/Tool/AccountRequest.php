<?php
namespace App\Http\Requests\Tool;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'title' => 'required|max:50',
        ];
    }
}

