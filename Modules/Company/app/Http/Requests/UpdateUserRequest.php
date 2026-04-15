<?php

namespace Modules\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!$this->user() || !$this->user()->isCompanyAdmin()) {
            return false;
        }

        // Ensure the target user belongs to the same company
        $targetUser = $this->route('user');
        return $targetUser && $targetUser->company_id === $this->user()->company_id;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(['company_admin', 'agent'])],
        ];
    }
}
