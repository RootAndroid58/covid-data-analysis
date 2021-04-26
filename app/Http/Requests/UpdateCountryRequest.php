<?php

namespace App\Http\Requests;

use App\Models\Country;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCountryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('country_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'slug' => [
                'string',
                'required',
                'unique:countries,slug,' . request()->route('country')->id,
            ],
            'phone_code' => [
                'string',
                'required',
            ],
            'region' => [
                'string',
                'required',
            ],
            'subregion' => [
                'string',
                'required',
            ],
            'emojiu' => [
                'string',
                'nullable',
            ],
        ];
    }
}
