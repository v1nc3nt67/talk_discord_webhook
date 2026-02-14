<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Controller;

use OCA\TalkDiscordWebhook\Db\WebhookMapper;
use OCA\TalkDiscordWebhook\Service\DiscordService;
use OCA\TalkDiscordWebhook\Service\TalkService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http;
use OCP\IRequest;

class WebhookController extends Controller
{
    private WebhookMapper $mapper;
    private DiscordService $discordService;
    private TalkService $talkService;

    public function __construct(string $appName, IRequest $request, WebhookMapper $mapper, DiscordService $discordService, TalkService $talkService)
    {
        parent::__construct($appName, $request);
        $this->mapper = $mapper;
        $this->discordService = $discordService;
        $this->talkService = $talkService;
    }

    /**
     * @NoAdminRequired
     * @NoCSRFRequired
     * @PublicPage
     */
    public function handle(string $token): JSONResponse
    {
        try {
            $webhook = $this->mapper->findByToken($token);
        }
        catch (\Exception $e) {
            return new JSONResponse(['error' => 'Webhook not found'], Http::STATUS_NOT_FOUND);
        }

        $payload = $this->request->getParams();

        // Parse Discord payload
        $message = $this->discordService->formatMessage($payload);

        if (empty($message)) {
            return new JSONResponse(['error' => 'Empty message content'], Http::STATUS_BAD_REQUEST);
        }

        // Send to Talk
        try {
            // We use the user_id associated with the webhook to post the message
            $this->talkService->sendMessageAsUser($webhook->getRoomToken(), $message, $webhook->getUserId());
        }
        catch (\Exception $e) {
            return new JSONResponse(['error' => 'Failed to send message: ' . $e->getMessage()], Http::STATUS_INTERNAL_SERVER_ERROR);
        }

        return new JSONResponse(['success' => true]);
    }
}
