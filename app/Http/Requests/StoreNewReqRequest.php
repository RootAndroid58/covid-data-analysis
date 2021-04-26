<?php

namespace App\Http\Requests;

use App\Models\NewReq;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreNewReqRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('new_req_create');
    }

    public function rules()
    {
        return [
            'catogary' => [
                'string',
                'nullable',
            ],
            'country' => [
                'string',
                'nullable',
            ],
            'state' => [
                'string',
                'nullable',
            ],
            'city' => [
                'string',
                'nullable',
            ],
        ];
    }
}
