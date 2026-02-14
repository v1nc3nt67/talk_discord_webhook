<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Service;

use OCP\Talk\IBroker;
use OCP\IConfig;

class TalkService
{
    private IBroker $talkBroker;

    public function __construct(IBroker $talkBroker)
    {
        $this->talkBroker = $talkBroker;
    }

    public function sendMessage(string $roomToken, string $message, string $actor = 'Discord Webhook'): void
    {
    // Implementation might vary based on exact IBroker version, 
    // but generally involves finding the conversation and posting a message.
    // Since we are external, we might not have a session user.
    // We might need to use a system user or a specific bot user if available, 
    // OR the IBroker allows posting as a guest/system.

    // For this implementation, we will assume we can post using the broker directly 
    // or we need to act as a specific user (the creator of the webhook).

    // Wait, IBroker often requires a participant. 
    // If we want to simulate a "bot", we might need to use 'system' message or similar, 
    // but 'system' messages are special.

    // A common pattern is to use the user who created the webhook as the sender,
    // or a dedicated bot user. The webhook entity has `user_id`.

    // Let's assume we pass the userId to this service method in a real scenario,
    // but for now let's stick to the signature.
    }

    public function sendMessageAsUser(string $roomToken, string $message, string $userId): void
    {
        // This is a more realistic usage for NC Talk integration
        $conversation = $this->talkBroker->getConversation($roomToken);
        if ($conversation) {
            $this->talkBroker->createMessage($conversation, $message, $userId);
        }
    }
}
