<?php
use Easy\User\Contracts\Management\AuthenticateInterface;
use Easy\User\Services\Management\Authenticate;

return [
     'user-authentication' => [
         'source' => Authenticate::class,
         'destination' => AuthenticateInterface::class,
         'remove' => false,
         'is_singleton' => false
     ]
];
