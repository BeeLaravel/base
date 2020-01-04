<?php
namespace App\Http\Requests\Music;

class SongRequest extends Request {
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
