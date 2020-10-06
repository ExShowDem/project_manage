<?php

return [
    'pagination' => [
        'per_page' => 20,
        'max_per_page' => 100,
    ],
    'code' => [
        'common' => [
            'system_error' => -1,
            'request_success' => 0,
            'request_error' => 1,
            'validate_failed' => 2,
            'create_failed' => 3,
            'update_failed' => 4,
            'delete_failed' => 5,
            'activate_failed' => 6,
            'keyword_not_found' => 7,
            'post_not_found' => 7,
            'not_authorized' => 403
        ],
        'auth' => [
            'invalid_credentials' => 11,
            'token_absent' => 12,
            'token_expired' => 13,
            'token_blacklisted' => 14,
            'token_invalid' => 15,
            'user_not_found' => 16,
        ],
        'task' => [
            'handled_task' => 1000
        ]
    ],
    'date_format' => 'd/m/Y'
];
