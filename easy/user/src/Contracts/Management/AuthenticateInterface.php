<?php

namespace Easy\User\Contracts\Management;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;

interface Authenticate
{
    /**
     * @return Authenticatable|null
     * @throws ValidationException
     */
    public function authenticate(): ?Authenticatable;
}
