<?php

declare(strict_types=1);

namespace Easy\User\Contracts\Management;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

interface AuthenticateInterface
{
    /**
     * @param array $inputs
     * @param Request $request
     * @param string $guard
     * @return Authenticatable|null
     * @throws ValidationException
     */
    public function authenticate(array $inputs, Request $request, string $guard = 'web'): ?Authenticatable;

    /**
     * @param $inputs
     * @param string $guard
     * @param string $model
     * @return Authenticatable|null
     */
    public function registration ($inputs, string $guard = 'web', string $model = \App\Models\User::class): ?Authenticatable;
}
