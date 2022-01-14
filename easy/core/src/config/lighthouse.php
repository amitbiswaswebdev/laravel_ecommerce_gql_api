<?php

return [
    'route' => [
        'middleware' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Nuwave\Lighthouse\Support\Http\Middleware\AcceptJson::class,
            \Nuwave\Lighthouse\Support\Http\Middleware\AttemptAuthentication::class,
        ]
    ],
    'guard' => 'sanctum'
];
