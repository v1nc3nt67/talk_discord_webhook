<?php

declare(strict_types=1);

namespace OCA\TalkDiscordWebhook\Service;

use OCA\TalkDiscordWebhook\Db\Webhook;
use OCA\TalkDiscordWebhook\Db\WebhookMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\Security\ISecureRandom;

class WebhookService {

    private WebhookMapper $mapper;
    private ISecureRandom $secureRandom;

    public function __construct(WebhookMapper $mapper, ISecureRandom $secureRandom) {
        $this->mapper = $mapper;
        $this->secureRandom = $secureRandom;
    }

    public function findByToken(string $token): ?Webhook {
        try {
            return $this->mapper->findByToken($token);
        } catch (DoesNotExistException $e) {
            return null;
        }
    }

    /**
     * @return Webhook[]
     */
    public function findAllByUser(?string $userId): array {
        if ($userId === null) {
            return [];
        }
        return $this->mapper->findAllByUser($userId);
    }

    /**
     * @return Webhook[]
     */
    public function findAllByConversation(string $conversationToken): array {
        return $this->mapper->findAllByConversation($conversationToken);
    }

    public function findByIdAndUser(int $id, ?string $userId): ?Webhook {
        if ($userId === null) {
            return null;
        }
        try {
            return $this->mapper->findByIdAndUser($id, $userId);
        } catch (DoesNotExistException $e) {
            return null;
        }
    }

    public function create(
        string $title,
        string $description,
        string $conversationToken,
        ?string $userId
    ): Webhook {
        $webhook = new Webhook();
        $webhook->setTitle($title);
        $webhook->setDescription($description);
        $webhook->setConversationToken($conversationToken);
        $webhook->setUserId($userId ?? '');
        $webhook->setToken($this->generateToken());
        $webhook->setCreatedAt(date('Y-m-d H:i:s'));

        return $this->mapper->insert($webhook);
    }

    public function delete(Webhook $webhook): void {
        $this->mapper->delete($webhook);
    }

    private function generateToken(): string {
        return $this->secureRandom->generate(
            48,
            ISecureRandom::CHAR_ALPHANUMERIC
        );
    }
}