<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Settings;

use OCP\Settings\IIconSection;

class PersonalSection implements IIconSection
{
    public function getIcon(): string
    {
        return 'icon-webhook'; // Standard NC icon class or path
    }

    public function getID(): string
    {
        return 'talk_discord_webhook';
    }

    public function getName(): string
    {
        return 'Discord Webhooks';
    }

    public function getPriority(): int
    {
        return 10;
    }
}
