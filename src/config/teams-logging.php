<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Message Title
    |--------------------------------------------------------------------------
    |
    | Set message title. Default is app.name if set null
    |
    */
    'message_title' => env('TEAMS_MESSAGE_TITLE', env('app_name')),

    /*
    |--------------------------------------------------------------------------
    | Enhanced Title Info
    |--------------------------------------------------------------------------
    |
    | Add the error type and app.url in the message title. Allowed value: false (default) and true
    |
    */
    'verbose_title' => true,

    /*
    |--------------------------------------------------------------------------
    | Message Style
    |--------------------------------------------------------------------------
    |
    | Set message style. Available style: simple, card (default: simple)
    |
    */
    'message_style' => env('TEAMS_MESSAGE_STYLE', env('app_name')),

    /*
    |--------------------------------------------------------------------------
    | Display Avatars
    |--------------------------------------------------------------------------
    |
    | Display avatars in teams notification. When disallowed, avatars value will
    | not be used. Allowed value: true (default) and false
    |
    */
    'show_avatars' => true,

    /*
    |--------------------------------------------------------------------------
    | Display Error Type
    |--------------------------------------------------------------------------
    |
    | Display error type in teams notification. Allowed value: true (default) and false
    |
    */
    'show_type' => true,

    /*
    |--------------------------------------------------------------------------
    | Display Date
    |--------------------------------------------------------------------------
    |
    | Display the date of the logged notification. Allowed value: false (default) and true
    |
    */
    'show_date' => true,

    /*
    |--------------------------------------------------------------------------
    | Display Date Format
    |--------------------------------------------------------------------------
    |
    | The format of the date of the logged notification. Allowed value: Any format accepted by
    | DateTimeInterface::format() (). Default: 'D, M d Y H:i:s e'
    |
    */
    'date_format' => 'D, M d Y H:i:s e',

    /*
    |--------------------------------------------------------------------------
    | Display Route
    |--------------------------------------------------------------------------
    |
    | Display route (if available) called which led to the logged notification. Allowed value: false (default) and true
    |
    */
    'show_route' => true,

    /*
    |--------------------------------------------------------------------------
    | Display Action (Controller@Method)
    |--------------------------------------------------------------------------
    |
    | Display controller and method (if available) that led to the logged notification. Allowed value: false (default) and true
    |
    */
    'show_action' => true,

    /*
    |--------------------------------------------------------------------------
    | Display User
    |--------------------------------------------------------------------------
    |
    | Display authenticated user (if available) for whom the log was triggered. Allowed value: false (default) and true
    |
    */
    'show_user' => true,

    /*
    |--------------------------------------------------------------------------
    | Display Exception
    |--------------------------------------------------------------------------
    |
    | Display exception trace. Allowed value: false (default) and true
    |
    */
    'show_exception' => true,

    /*
    |--------------------------------------------------------------------------
    | Exception Limit
    |--------------------------------------------------------------------------
    |
    | Display number of exception lines. Set -1 for all Default: 10
    |
    */
    'exception_limit' => 10,

    /*
    |--------------------------------------------------------------------------
    | Verify SSL
    |--------------------------------------------------------------------------
    |
    | Set false on localhost otherwise set true Default is ture
    |
    */
    'verify_ssl' => env('TEAMS_VERIFY_SSL', true),

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    |
    | Enable message
    |
    */
    'messages' => [
        'emergency' => true,
        'alert' => false,
        'critical' => true,
        'error' => true,
        'warning' => false,
        'notice' => false,
        'info' => false,
        'debug' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Avatars
    |--------------------------------------------------------------------------
    |
    | Avatars icon to display for each log type
    |
    */
    'avatars' => [
        'emergency' => 'https://adorable-avatars.broken.services/face/eyes7/nose7/mouth7/721C24',
        'alert' => 'https://adorable-avatars.broken.services/face/eyes7/nose7/mouth6/AF2432',
        'critical' => 'https://adorable-avatars.broken.services/face/eyes7/nose7/mouth5/FF0000',
        'error' => 'https://adorable-avatars.broken.services/face/eyes7/nose7/mouth9/FF8000',
        'warning' => 'https://adorable-avatars.broken.services/face/eyes6/nose7/mouth10/FFEEBA',
        'notice' => 'https://adorable-avatars.broken.services/face/eyes6/nose7/mouth3/B8DAFF',
        'info' => 'https://adorable-avatars.broken.services/face/eyes5/nose7/mouth1/BEE5EB',
        'debug' => 'https://adorable-avatars.broken.services/face/eyes5/nose7/mouth1/C3E6CB',
    ],

    /*
    |--------------------------------------------------------------------------
    | Colours
    |--------------------------------------------------------------------------
    |
    | Colours to display for each log type
    |
    */
    'colours' => [
        'emergency' => '721C24',
        'alert' => 'AF2432',
        'critical' => 'FF0000',
        'error' => 'FF8000',
        'warning' => 'FFEEBA',
        'notice' => 'B8DAFF',
        'info' => 'BEE5EB',
        'debug' => 'C3E6CB',
    ],
];
