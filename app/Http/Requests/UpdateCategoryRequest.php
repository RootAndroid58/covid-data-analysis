<?php

namespace App\Http\Requests;

use App\Models\Category;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_edit');
    }

    public function rules()
    {
        return [
            'category_name' => [
                'string',
                'required',
                'unique:categories,category_name,' . request()->route('category')->id,
            ],
            'slug' => [
                'string',
                'required',
                'unique:categories,slug,' . request()->route('category')->id,
            ],
        ];
    }
}
