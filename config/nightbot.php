<?php

return [

    /**
     *
     */
    'client_id' => env('NIGHTBOT_CLIENT_ID', ''),

    /**
     *
     */
    'secret_id' => env('NIGHTBOT_SECRET_ID', ''),

    /**
     *
     */
    'redirect_url' => env('NIGHTBOT_REDIRECT_URL', ''),

    /**
     *
     */
    'scopes' => [
        'channel',
        'channel_send',
        'commands',
        'commands_default',
        'regulars',
        'song_requests',
        'song_requests_queue',
        'song_requests_playlist',
        'spam_protection',
        'subscribers',
        'timers',
    ],

    /**
     *
     */
    'state' => env('NIGHTBOT_STATE', 'yor_any_string'),

    /**
     *
     */
    'grant_type' => 'authorization_code',

    /**
     *
     */
    'response_type' => 'code',
];