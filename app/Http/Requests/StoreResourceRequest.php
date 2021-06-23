<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreResourceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('resource_create');
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
                'sometimes|nullable',
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
                'nullable',
                // 'required_if:phone_no',
                'string'
            ],
        ];
    }
}
