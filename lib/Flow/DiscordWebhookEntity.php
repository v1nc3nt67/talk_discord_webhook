<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Flow;

use OCP\EventDispatcher\IEventDispatcher;
use OCP\WorkflowEngine\IEntity;
use OCP\WorkflowEngine\IEntityEvent;
use OCP\WorkflowEngine\IRuleMatcher;
use OCP\IL10n;

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

    public function getDisplayName(): string
    {
        return $this->getName();
    }

    public function getEntityId(): string
    {
        return $this->getID();
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

    public function isLegitimatedForUserId(string $userId): bool
    {
        return true;
    }

    public function isDeprecatingEvents(): bool
    {
        return false;
    }

    /** @var IEntityEvent|null */
    protected $currentEvent;

    public function prepareRuleMatcher(IRuleMatcher $ruleMatcher, string $eventName, IEntityEvent $event): void
    {
        $this->currentEvent = $event;
        $ruleMatcher->setEntity($this);
    }

    public function getCurrentEvent(): ?IEntityEvent
    {
        return $this->currentEvent;
    }
}
