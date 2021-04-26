<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\State;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyStateRequest extends FormRequest  {
    public function authorize()
    {
        abort_if(Gate::denies('state_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return true;

    }
    public function rules()
    {
        return [
        'ids' => 'required|array',
            'ids.*' => 'exists:states,id',
        ];

    }

}
