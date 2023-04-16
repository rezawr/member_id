<?php

namespace App\Http\Requests\Api\V1\Award;

use Illuminate\Foundation\Http\FormRequest;

use Auth;
class PatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return empty(Auth::user()) ? false : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'type' => 'sometimes|string|in:vouchers,products,others',
            'exchanges_point' => 'sometimes|integer',
            'image' => 'sometimes|image',
        ];
    }
}
