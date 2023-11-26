<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class ServicesRequest extends FormRequest
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
            'service_name'        => 'required|string|max:255',
            'service_code'        => 'required|string|max:255',
            'service_description' => 'required|string|max:255',
            'amount'              => 'required|numeric',
            'category'            => 'required|integer'
        ];
    }
}
