<?php

namespace App\Http\Requests\Api\V1\Award;

use Illuminate\Foundation\Http\FormRequest;

use Auth;
class GetRequest extends FormRequest
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
            'point_start' => 'nullable|integer',
            'point_end' => 'nullable|integer|gt:point_start',
            'type' => 'nullable|string'
        ];
    }
}
