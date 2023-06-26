<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ProductSubmitRequest extends FormRequest
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
        $method = request()->input('_method');
        Log::info($method);
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'integer'],
        ];

        if ($method == 'post') { // Create method
            $rules['image'] = ['required', 'image', 'mimes:jpeg,png', 'max:5120'];
        } else if ($method == 'put' || $method == 'patch') { // Update method
            $rules['image'] = ['nullable', 'image', 'mimes:jpeg,png', 'max:5120'];
        }

        return $rules;
    }
}
