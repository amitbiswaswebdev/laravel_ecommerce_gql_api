<?php
use Easy\User\Contracts\Management\AuthenticateInterface;
use Easy\User\Services\Management\Authenticate;

return [
     'user-authentication' => [
         'source' => AuthenticateInterface::class,
         'destination' => Authenticate::class,
         'remove' => false,
         'is_singleton' => false
     ]
];
