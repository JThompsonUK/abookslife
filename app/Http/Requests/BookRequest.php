<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'book_title'    => 'required|string',
            'book_author'   => 'required|string',
            'image' => 'image|max:1024',

        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
//    public function messages()
//    {
//        return [
//            'thumbnail_id.required'           => 'A thumbnail image is required',
//        ];
//    }
}
