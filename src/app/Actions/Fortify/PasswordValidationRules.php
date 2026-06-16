<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Validator;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used by password verification.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return ['required', 'string', 'min:8', 'confirmed'];
    }
}