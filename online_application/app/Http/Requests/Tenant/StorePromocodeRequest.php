<?php

namespace App\Http\Requests\Tenant;

use App\Rules\School\PromocodeType;
use Illuminate\Foundation\Http\FormRequest;

class StorePromocodeRequest extends FormRequest
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
            'type'        => 'required|string|max:255',
            'reward'      => ['required', 'numeric', 'min:1', new PromocodeType($this->type)],
            'quantity'    => 'nullable|numeric|max:1000',
            'code'        => 'nullable|regex:/^[a-zA-Z0-9][-a-zA-Z0-9]*$/',
            'commence_at' => 'sometimes|nullable|date|after:yesterday',
            'expires_at'  => 'sometimes|nullable|date|after:commence_at',
        ];
    }
}
