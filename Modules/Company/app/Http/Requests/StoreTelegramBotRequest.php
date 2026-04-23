<?php

namespace Modules\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTelegramBotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCompanyAdmin();
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:255'],
            'token'  => ['required', 'string', 'unique:telegram_bots,token'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
