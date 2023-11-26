<?php

namespace App\Http\Requests\Tenant;

use App\Rules\School\UniqueProgram;
use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
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
            'title'        => 'required',
            'program_type' => 'required',
            'campuses'     => 'required',
            'slug'         => ['required', new UniqueProgram('slug'), 'regex:/^[a-zA-Z0-9-_,]+$/u'],
            'properties'   => 'required',
        ];
    }
}
