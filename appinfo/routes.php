<?php

return [
    'routes' => [
        // Public Webhook Endpoint
        [
            'name' => 'webhook#handle',
            'url' => '/api/v1/webhook/{token}',
            'verb' => 'POST'
        ],
        // Internal API for managing webhooks
        [
            'name' => 'settings#index',
            'url' => '/settings',
            'verb' => 'GET'
        ],
        [
            'name' => 'settings#create',
            'url' => '/settings',
            'verb' => 'POST'
        ],
        [
            'name' => 'settings#destroy',
            'url' => '/settings/{id}',
            'verb' => 'DELETE'
        ],
    ]
];
