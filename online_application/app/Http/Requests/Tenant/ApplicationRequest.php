<?php

namespace App\Http\Requests\Tenant;

use App\Tenant\Models\Application;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
           'title'          => 'required|unique:applications|max:255',
            'description'   => 'required',
        ];
    }
}
