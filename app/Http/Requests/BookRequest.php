<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Lang;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:191',
            'description' => 'required|min:5|max:2500',
            'author' => 'required|min:3|max:100',
            'categories' => 'required',
            'avatar' => 'max:2000',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => trans('validation.required'),
            'title.min' => trans('validation.min.string'),
            'title.max' => trans('validation.max.string'),
            'description.required' => trans('validation.required'),
            'description.min' => trans('validation.min.string'),
            'author.required' => trans('validation.required'),
            'author.min' => trans('validation.min.string'),
            'author.max' => trans('validation.max.string'),
            'categories.required' => trans('validation.required'),
            'avatar.uploaded' => trans('validation.max_size'),
        ];
    }
}
