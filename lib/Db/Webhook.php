<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Db;

use OCP\AppFramework\Db\Entity;

class Webhook extends Entity
{
    protected $token;
    protected $roomToken;
    protected $name;
    protected $userId;

    public function __construct()
    {
        $this->addType('token', 'string');
        $this->addType('roomToken', 'string');
        $this->addType('name', 'string');
        $this->addType('userId', 'string');
    }
}
