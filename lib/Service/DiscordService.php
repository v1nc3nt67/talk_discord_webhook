<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Service;

use OCP\Talk\IBroker;
use OCP\IConfig;

class DiscordService
{

    /** @var TalkService */
    private $talkService;

    public function __construct(TalkService $talkService)
    {
        $this->talkService = $talkService;
    }

    public function processWebhook(string $roomToken, array $payload): void
    {
        $message = $this->formatMessage($payload);
        if (!empty($message)) {
            // Assuming the room token is the user ID or room token
            // Here we need to find out *who* posts the message.
            // Let's assume we post as a guest or system (if possible) or use a predefined user.
            // For now, let's just try to send to the room.
            $this->talkService->sendMessage($roomToken, $message);
        }
    }

    public function formatMessage(array $payload): string
    {
        $message = '';

        if (!empty($payload['content'])) {
            $message .= $payload['content'] . "\n\n";
        }

        if (!empty($payload['embeds']) && is_array($payload['embeds'])) {
            foreach ($payload['embeds'] as $embed) {
                if (isset($embed['title'])) {
                    $message .= "**" . $embed['title'] . "**\n";
                }
                if (isset($embed['description'])) {
                    $message .= $embed['description'] . "\n";
                }
                if (isset($embed['fields']) && is_array($embed['fields'])) {
                    foreach ($embed['fields'] as $field) {
                        $name = $field['name'] ?? '';
                        $value = $field['value'] ?? '';
                        $message .= "*$name*: $value\n";
                    }
                }
                // Add footer if present
                if (isset($embed['footer']['text'])) {
                    $message .= "_" . $embed['footer']['text'] . "_\n";
                }
                $message .= "\n";
            }
        }

        return trim($message);
    }
}
