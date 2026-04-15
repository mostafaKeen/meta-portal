<?php

namespace Modules\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        $companyId = $this->route('company');

        return [
            'name'          => ['required', 'string', 'max:255'],
            'domain_slug'   => ['required', 'string', 'max:255', Rule::unique('companies', 'domain_slug')->ignore($companyId), 'alpha_dash'],
            'email'         => ['nullable', 'email', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'address'       => ['nullable', 'string', 'max:1000'],
            'website'       => ['nullable', 'url', 'max:255'],
            'logo'          => ['nullable', 'image', 'max:2048'],
            'status'        => ['required', Rule::in(['active', 'inactive', 'suspended'])],
            // Bitrix24
            'b24_domain'        => ['nullable', 'string', 'max:255'],
            'b24_client_id'     => ['nullable', 'string', 'max:255'],
            'b24_client_secret' => ['nullable', 'string', 'max:255'],
            // Primary Admin Account
            'admin_name'     => ['required', 'string', 'max:255'],
            'admin_email'    => ['required', 'email', 'max:255'],
            'admin_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
