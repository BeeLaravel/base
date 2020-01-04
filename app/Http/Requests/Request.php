<?php
namespace App\Http\Requests;

class Request extends \Illuminate\Foundation\Http\FormRequest {
	public function authorize() {
        return true;
    }
}
