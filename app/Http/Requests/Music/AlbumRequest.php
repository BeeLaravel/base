<?php
namespace App\Http\Requests\Music;

class AlbumRequest extends Request {
    public function rules() {
        $data = [
            'title' => 'required',
            'sort' => 'bail|integer|min:0|max:255',
        ];

        return $data;
    }
    public function messages() {
        return [];
    }
}
