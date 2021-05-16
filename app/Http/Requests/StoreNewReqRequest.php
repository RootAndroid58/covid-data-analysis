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
            'model' => [
                'required',
            ],
            'email_id' => [
                'required',
                'integer',
            ],
            'data' => [
                'required',
            ],
            'message' => [
                'string',
                'nullable',
            ],
        ];
    }
}
