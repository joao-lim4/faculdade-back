<?php

return [

    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'email-ssl.com.br'),
    'port' => env('MAIL_PORT', 465),
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'comunicacao@worktab.com.br'),
        'name' => env('MAIL_FROM_NAME', 'Sunvibes'),
    ],
    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
    'username' => env('MAIL_USERNAME', 'comunicacao@worktab.com.br'),
    'password' => env('MAIL_PASSWORD', 'Wtpwd1206#')

];
