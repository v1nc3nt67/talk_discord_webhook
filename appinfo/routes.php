<?php

return [
    'routes' => [
        // Public Webhook Endpoint
        [
            'name' => 'webhook#handle',
            'url' => '/api/v1/webhook/{token}',
            'verb' => 'POST'
        ],
    ]
];
