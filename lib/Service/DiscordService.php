<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Service;

class DiscordService
{

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
