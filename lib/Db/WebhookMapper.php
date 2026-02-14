<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\DB\IDBConnection;

class WebhookMapper extends QBMapper
{
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'talk_discord_webhooks', Webhook::class);
    }

    public function findByToken(string $token): Webhook
    {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from('talk_discord_webhooks')
            ->where($qb->expr()->eq('token', $qb->createNamedParameter($token)));

        return $this->findEntity($qb);
    }

    public function findAllByUser(string $userId): array
    {
        $qb = $this->db->getQueryBuilder();

        $qb->select('*')
            ->from('talk_discord_webhooks')
            ->where($qb->expr()->eq('user_id', $qb->createNamedParameter($userId)));

        return $this->findEntities($qb);
    }
}
