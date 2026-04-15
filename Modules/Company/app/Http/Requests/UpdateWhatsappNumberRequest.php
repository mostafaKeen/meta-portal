<?php

namespace Modules\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWhatsappNumberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCompanyAdmin();
    }

    public function rules(): array
    {
        $numberId = $this->route('whatsapp');

        return [
            'type'          => ['required', Rule::in(['api', 'qr'])],
            'phone_number'  => ['required', 'string', 'max:20', Rule::unique('whatsapp_numbers', 'phone_number')->ignore($numberId)],
            'status'        => ['required', Rule::in(['active', 'inactive'])],

            // API fields
            'app_name'      => ['required_if:type,api', 'nullable', 'string', 'max:255'],
            'app_id'        => ['required_if:type,api', 'nullable', 'string', 'max:255'],
            'app_token'     => ['required_if:type,api', 'nullable', 'string'],

            // QR fields
            'session_name'  => ['required_if:type,qr', 'nullable', 'string', 'max:255', Rule::unique('whatsapp_numbers', 'session_name')->ignore($numberId)],
        ];
    }

    public function messages(): array
    {
        return [
            'app_name.required_if'    => 'App Name is required for API connections.',
            'app_id.required_if'      => 'App ID is required for API connections.',
            'app_token.required_if'   => 'App Token is required for API connections.',
            'session_name.required_if' => 'Session Name is required for QR connections.',
        ];
    }
}
