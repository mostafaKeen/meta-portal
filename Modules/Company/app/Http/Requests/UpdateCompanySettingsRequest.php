<?php

namespace Modules\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanySettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCompanyAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Contact Info
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['nullable', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:50'],
            'address'           => ['nullable', 'string', 'max:1000'],
            'website'           => ['nullable', 'url', 'max:255'],
            'logo'              => ['nullable', 'image', 'max:2048'],
            
            // Bitrix24 Integration
            'b24_domain'        => ['nullable', 'string', 'max:255'],
            'b24_client_id'     => ['nullable', 'string', 'max:255'],
            'b24_client_secret' => ['nullable', 'string', 'max:255'],

            // Meta CAPI
            'fb_pixel_id'       => ['nullable', 'string', 'max:255'],
            'fb_access_token'   => ['nullable', 'string', 'max:2000'],
        ];

    }
}
