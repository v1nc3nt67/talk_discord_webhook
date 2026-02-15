<?php
declare(strict_types=1);

namespace OCA\TalkDiscordWebhook\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Util;

class Application extends App implements IBootstrap {

    public const APP_ID = 'talk_discord_webhook';

    public function __construct(array $params = []) {
        parent::__construct(self::APP_ID, $params);
    }

    public function register(IRegistrationContext $context): void {
    }

    public function boot(IBootContext $context): void {
        // Load the frontend script on Talk pages so it can inject
        // the webhook settings into the conversation settings sidebar.
        Util::addScript(self::APP_ID, 'talk_discord_webhook-talk_discord_webhook');
    }
}