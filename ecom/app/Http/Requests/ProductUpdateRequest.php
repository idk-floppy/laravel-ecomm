<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseProductSubmitRequest;

class ProductUpdateRequest extends BaseProductSubmitRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            "image" => ['nullable', 'sometimes', 'image', 'mimes:jpeg,png', 'max:5120'],
        ]);
    }
}
