<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Flow;

use OCP\EventDispatcher\IEventDispatcher;
use OCP\WorkflowEngine\IEntity;
use OCP\WorkflowEngine\IEntityEvent;

class DiscordWebhookEntity implements IEntity
{
    /** @var IEventDispatcher */
    protected $dispatcher;

    public function __construct(IEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getID(): string
    {
        return 'talk_discord_webhook';
    }

    public function getName(): string
    {
        return 'Discord Webhook';
    }

    public function getIcon(): string
    {
        return 'icon-webhook'; // Needs to be a valid icon class
    }

    public function getEvents(): array
    {
        return [
            'received' => 'Webhook received',
        ];
    }

    public function prepareRuleMatcher(IEntityEvent $event, $subject, array $data): void
    {
    // This is called when the event is triggered, to setup checks
    // For example, we could check the "Token"
    }

    public function isDeprecatingEvents(): bool
    {
        return false;
    }
}
