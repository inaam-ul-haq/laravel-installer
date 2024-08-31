<?php

namespace Inaam\Installer\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Inaam\Installer\Rules\LicenseKeyRule;

class LicenseKeyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'liquid_license_domain_key' => ['required', 'string', 'max:255', new LicenseKeyRule()],
        ];
    }
}
