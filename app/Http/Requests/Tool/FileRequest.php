<?php
namespace App\Http\Requests\Tool;

class FileRequest extends Request {
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'file' => 'required|file',
        ];
    }
}
