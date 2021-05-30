<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateResourceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('resource_edit');
    }

    public function rules()
    {
        return [
            'city_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'nullable',
            ],
            'phone_no' => [
                'string',
                'sometimes',
            ],
            'email' => [
                'string',
                'nullable',
            ],
            'details' => [
                'string',
                'nullable',
            ],
            'url' => [
                'string',
                'required_without:phone_no|nullable',
            ],
        ];
    }
}
