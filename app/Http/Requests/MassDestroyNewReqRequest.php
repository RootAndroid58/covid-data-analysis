<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\NewReq;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyNewReqRequest extends FormRequest  {





    public function authorize()
    {
        abort_if(Gate::denies('new_req_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;

    }
    public function rules()
    {

        return [
        'ids' => 'required|array',
            'ids.*' => 'exists:new_reqs,id',
        ];

    }

}
