<?php
namespace App\Http\Requests\Application;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        switch ( $this->method() ) {
            case 'POST':
                $return = [
                    'title' => 'required|max:50',
                ];
            break;
            case 'PUT':
            case 'PATCH':
                $return = [
                    'title' => 'max:50',
                    'content' => 'required',
                ];
            break;
            default:
                $return = [];
        }

        $return = array_merge($return, [
            'sort' => 'bail|integer|min:0|max:255',
        ]);

        return $return;
    }
    public function messages() {
        return [];
    }
}
