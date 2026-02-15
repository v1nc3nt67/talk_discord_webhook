<?php

declare(strict_types=1);

namespace OCA\TalkDiscordWebhook\Db;

use OCP\AppFramework\Db\Entity;
use JsonSerializable;

/**
 * @method string getTitle()
 * @method void setTitle(string $title)
 * @method string getDescription()
 * @method void setDescription(string $description)
 * @method string getToken()
 * @method void setToken(string $token)
 * @method string getConversationToken()
 * @method void setConversationToken(string $conversationToken)
 * @method string getUserId()
 * @method void setUserId(string $userId)
 * @method string getCreatedAt()
 * @method void setCreatedAt(string $createdAt)
 */
class Webhook extends Entity implements JsonSerializable {

    protected string $title = '';
    protected string $description = '';
    protected string $token = '';
    protected string $conversationToken = '';
    protected string $userId = '';
    protected string $createdAt = '';

    public function __construct() {
        $this->addType('id', 'integer');
        $this->addType('title', 'string');
        $this->addType('description', 'string');
        $this->addType('token', 'string');
        $this->addType('conversationToken', 'string');
        $this->addType('userId', 'string');
        $this->addType('createdAt', 'string');
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'token' => $this->token,
            'conversationToken' => $this->conversationToken,
            'userId' => $this->userId,
            'createdAt' => $this->createdAt,
        ];
    }
}