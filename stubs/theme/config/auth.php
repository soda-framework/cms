<?php

return [
    'provider' => [
        'driver' => 'eloquent',
        'model'  => Themes\SodaExample\Models\User::class,
    ],
    'guard' => [
        'driver'   => 'session',
        'provider' => 'soda-example',
    ],
    'password' => [
        'provider' => 'soda-example',
        'email'    => 'soda-example::auth.emails.password',
        'table'    => 'password_resets',
        'expire'   => 60,
    ],
];
