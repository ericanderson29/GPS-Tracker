<?php declare(strict_types=1);

namespace App\Domains\UserSession\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class Success extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'auth' => ['bail', 'required'],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'auth.required' => __('validator.auth-required'),
        ];
    }
}
