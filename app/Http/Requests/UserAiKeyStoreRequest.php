<?php

namespace App\Http\Requests;

use App\Enum\AiProviderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserAiKeyStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:225', 'string'],
            'provider' => ['required', Rule::enum(AiProviderEnum::class)],
            'api_key' => ['required', 'max:225'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function getRedirectUrl()
    {
        return url()->previous().'#add-section';
    }
}
