<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCA\TalkDiscordWebhook\Flow\DiscordWebhookEntity;
use OCA\TalkDiscordWebhook\Flow\SendDiscordToTalkOperation;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\WorkflowEngine\Events\RegisterEntitiesEvent;
use OCP\WorkflowEngine\Events\RegisterOperationsEvent;

class Application extends App implements IBootstrap
{
    public const APP_ID = 'talk_discord_webhook';

    public function __construct(array $urlParams = [])
    {
        parent::__construct(self::APP_ID, $urlParams);
    }

    public function register(IRegistrationContext $context): void
    {
    }

    public function boot(IBootContext $context): void
    {
        /** @var IEventDispatcher $dispatcher */
        $dispatcher = $context->getServerContainer()->get(IEventDispatcher::class);

        $dispatcher->addListener(RegisterEntitiesEvent::class , function (RegisterEntitiesEvent $event) use ($context) {
            $entity = $context->getServerContainer()->get(DiscordWebhookEntity::class);
            $event->registerEntity($entity);
        });

        $dispatcher->addListener(RegisterOperationsEvent::class , function (RegisterOperationsEvent $event) use ($context) {
            $operation = $context->getServerContainer()->get(SendDiscordToTalkOperation::class);
            $event->registerOperation($operation);
        });
    }
}
