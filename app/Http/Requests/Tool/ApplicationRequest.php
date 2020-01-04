<?php
namespace App\Http\Requests\Tool;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ApplicationRequest extends FormRequest {
    public function authorize() {
        return true;
    }
    public function rules() {
        switch ( $this->method() ) {
            case 'POST':
                $return = [
                    'title' => 'required|max:50',
                    'slug' => [
                        'required',
                        'max:50',
                        'unique:applications',
                    ],
                ];
            break;
            case 'PUT':
            case 'PATCH':
                $return = [
                    'title' => 'max:50',
                    'slug' => [
                        'required',
                        'max:50',
                        Rule::unique('applications')->where(function ($query) {
                        	return $query;
                        })->ignore($this->route('application')),
                    ],
                ];
            break;
            default:
                $return = [];
        }

        // $return = array_merge($return, [
        // ]);

        return $return;
    }
}
