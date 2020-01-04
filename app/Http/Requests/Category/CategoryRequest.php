<?php
namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest {
    public function authorize() {
        return true;
        // $comment = Comment::find($this->route('comment'));
        // return $comment && $this->user()->can('update', $comment);
    }
    public function rules() {
        return [
            'title' => 'required',
            'slug' => 'required',
            'sort' => 'bail|integer|min:0|max:255',
        ];
    }
    public function messages() {
        return [];
    }
    // public function withValidator($validator) {
        // $validator->after(function ($validator) {
        //     if ( $this->somethingElseIsInvalid() ) {
        //         $validator->errors()->add('field', 'Something is wrong with this field!');
        //     }
        // });
    // }
}
