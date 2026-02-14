<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Flow;

use OCA\TalkDiscordWebhook\Service\DiscordService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\WorkflowEngine\IOperation;
use OCP\WorkflowEngine\IRuleMatcher;
use OCP\Talk\IBroker;

class SendDiscordToTalkOperation implements IOperation
{

    /** @var IBroker */
    protected $talkBroker;

    /** @var DiscordService */
    protected $discordService;

    public function __construct(IBroker $talkBroker, DiscordService $discordService)
    {
        $this->talkBroker = $talkBroker;
        $this->discordService = $discordService;
    }

    public function getDisplayName(): string
    {
        return 'Send to Talk (Discord Format)';
    }

    public function getDescription(): string
    {
        return 'Posts the incoming Discord webhook content to a Talk conversation';
    }

    public function getIcon(): string
    {
        return 'icon-talk';
    }

    public function isAvailable(IRuleMatcher $ruleMatcher): bool
    {
        return $ruleMatcher->getEntity()->getID() === 'talk_discord_webhook';
    }

    public function validateOperation(string $name, array $checks, string $validateOperation): void
    {
    // Validation logic
    }

    public function onValidateOperation(string $name, array $checks, string $validateOperation): void
    {
    // Validation logic
    }

    public function getDefinition(): array
    {
        return [
            'roomToken' => [
                'type' => 'text',
                'label' => 'Talk Conversation Token',
                'placeholder' => 'e.g. xxxxxxxx',
                'required' => true,
            ]
        ];
    }

    public function run(string $name, array $checks, string $operation, array $mediaTypes = []): void
    {
    // This is where we execute the action.
    // The payload from the webhook should be available in the entity event context?
    // Actually, normally the EntityEvent holds the subject.
    // We will need to figure out how to pass the JSON payload from Controller -> Entity -> Operation.
    // Usually `prepareRuleMatcher` sets up the environment. 
    // We might need to access the "subject" which will be our payload.
    }
}
