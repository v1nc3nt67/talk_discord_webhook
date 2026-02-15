<?php

return [
    'routes' => [
        // Public webhook endpoint (no auth required)
        [
            'name' => 'webhook#receive',
            'url'  => '/api/v1/webhook/{token}',
            'verb' => 'POST',
        ],
        // Admin/user endpoints for managing webhooks
        [
            'name' => 'webhook#create',
            'url'  => '/api/v1/webhooks',
            'verb' => 'POST',
        ],
        [
            'name' => 'webhook#index',
            'url'  => '/api/v1/webhooks',
            'verb' => 'GET',
        ],
        // List webhooks for a specific conversation
        [
            'name' => 'webhook#listByConversation',
            'url'  => '/api/v1/webhooks/conversation/{conversationToken}',
            'verb' => 'GET',
        ],
        [
            'name' => 'webhook#delete',
            'url'  => '/api/v1/webhooks/{id}',
            'verb' => 'DELETE',
        ],
    ],
];