<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap
{
    public const APP_ID = 'talk_discord_webhook';

    public function __construct(array $urlParams = [])
    {
        parent::__construct(self::APP_ID, $urlParams);
    }

    public function register(IRegistrationContext $context): void
    {
        $context->registerFlowProvider(DiscordWebhookEntity::class);
        $context->registerFlowProvider(SendDiscordToTalkOperation::class);
    }

    public function boot(IBootContext $context): void
    {
    // Boot logic
    }
}
