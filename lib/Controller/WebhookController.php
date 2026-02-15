<?php

declare(strict_types=1);

namespace OCA\TalkDiscordWebhook\Controller;

use OCA\TalkDiscordWebhook\Service\TalkService;
use OCA\TalkDiscordWebhook\Service\WebhookService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\PublicPage;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\IURLGenerator;
use Psr\Log\LoggerInterface;

class WebhookController extends Controller {

    private WebhookService $webhookService;
    private TalkService $talkService;
    private LoggerInterface $logger;
    private IURLGenerator $urlGenerator;
    private ?string $userId;

    public function __construct(
        string $appName,
        IRequest $request,
        WebhookService $webhookService,
        TalkService $talkService,
        LoggerInterface $logger,
        IURLGenerator $urlGenerator,
        ?string $userId
    ) {
        parent::__construct($appName, $request);
        $this->webhookService = $webhookService;
        $this->talkService = $talkService;
        $this->logger = $logger;
        $this->urlGenerator = $urlGenerator;
        $this->userId = $userId;
    }

    /**
     * Receive a Discord-formatted webhook payload and forward it to Talk.
     * This is a public endpoint — no authentication required.
     */
    #[PublicPage]
    #[NoCSRFRequired]
    public function receive(string $token): JSONResponse {
        $webhook = $this->webhookService->findByToken($token);

        if ($webhook === null) {
            return new JSONResponse(
                ['error' => 'Webhook not found'],
                Http::STATUS_NOT_FOUND
            );
        }

        $body = $this->request->getParams();
        // Also try reading raw JSON input
        if (empty($body['content']) && empty($body['embeds'])) {
            $rawBody = file_get_contents('php://input');
            if ($rawBody !== false) {
                $decoded = json_decode($rawBody, true);
                if (is_array($decoded)) {
                    $body = $decoded;
                }
            }
        }

        $content = $body['content'] ?? '';
        $username = $body['username'] ?? 'Webhook';
        $embeds = $body['embeds'] ?? [];

        // Build the message to send to Talk
        $message = $this->formatMessage($content, $username, $embeds);

        if (empty(trim($message))) {
            return new JSONResponse(
                ['error' => 'No content to send'],
                Http::STATUS_BAD_REQUEST
            );
        }

        try {
            $this->talkService->sendMessage(
                $webhook->getConversationToken(),
                $message,
                $webhook->getUserId()
            );
        } catch (\Exception $e) {
            $this->logger->error('Failed to send message to Talk: ' . $e->getMessage(), [
                'app' => 'talk_discord_webhook',
                'exception' => $e,
            ]);
            return new JSONResponse(
                ['error' => 'Failed to send message to Talk'],
                Http::STATUS_INTERNAL_SERVER_ERROR
            );
        }

        return new JSONResponse(['status' => 'ok'], Http::STATUS_OK);
    }

    /**
     * Create a new webhook for a Talk conversation.
     */
    #[NoAdminRequired]
    public function create(): JSONResponse {
        $title = $this->request->getParam('title', '');
        $description = $this->request->getParam('description', '');
        $conversationToken = $this->request->getParam('conversationToken', '');

        if (empty($conversationToken)) {
            return new JSONResponse(
                ['error' => 'conversationToken is required'],
                Http::STATUS_BAD_REQUEST
            );
        }

        $webhook = $this->webhookService->create(
            $title,
            $description,
            $conversationToken,
            $this->userId
        );

        $data = $webhook->jsonSerialize();
        $data['url'] = $this->buildWebhookUrl($webhook->getToken());

        return new JSONResponse($data, Http::STATUS_CREATED);
    }

    /**
     * List all webhooks for the current user.
     */
    #[NoAdminRequired]
    public function index(): JSONResponse {
        $webhooks = $this->webhookService->findAllByUser($this->userId);
        $result = array_map(function ($webhook) {
            $data = $webhook->jsonSerialize();
            $data['url'] = $this->buildWebhookUrl($webhook->getToken());
            return $data;
        }, $webhooks);

        return new JSONResponse($result, Http::STATUS_OK);
    }

    /**
     * List all webhooks for a specific conversation.
     */
    #[NoAdminRequired]
    public function listByConversation(string $conversationToken): JSONResponse {
        $webhooks = $this->webhookService->findAllByConversation($conversationToken);
        $result = array_map(function ($webhook) {
            $data = $webhook->jsonSerialize();
            $data['url'] = $this->buildWebhookUrl($webhook->getToken());
            return $data;
        }, $webhooks);

        return new JSONResponse($result, Http::STATUS_OK);
    }

    /**
     * Delete a webhook by ID.
     */
    #[NoAdminRequired]
    public function delete(int $id): JSONResponse {
        $webhook = $this->webhookService->findByIdAndUser($id, $this->userId);

        if ($webhook === null) {
            return new JSONResponse(
                ['error' => 'Webhook not found'],
                Http::STATUS_NOT_FOUND
            );
        }

        $this->webhookService->delete($webhook);

        return new JSONResponse(['status' => 'deleted'], Http::STATUS_OK);
    }

    /**
     * Build the full public URL for a webhook token.
     */
    private function buildWebhookUrl(string $token): string {
        return $this->urlGenerator->linkToRouteAbsolute(
            'talk_discord_webhook.webhook.receive',
            ['token' => $token]
        );
    }

    /**
     * Format a Discord-style payload into a plain-text message for Talk.
     */
    private function formatMessage(string $content, string $username, array $embeds): string {
        $parts = [];

        if (!empty($username)) {
            $parts[] = '**' . $username . '**';
        }

        if (!empty($content)) {
            $parts[] = $content;
        }

        foreach ($embeds as $embed) {
            $embedParts = [];

            if (!empty($embed['title'])) {
                $embedParts[] = '**' . $embed['title'] . '**';
            }
            if (!empty($embed['url'])) {
                $embedParts[] = $embed['url'];
            }
            if (!empty($embed['description'])) {
                $embedParts[] = $embed['description'];
            }
            if (!empty($embed['fields']) && is_array($embed['fields'])) {
                foreach ($embed['fields'] as $field) {
                    $name = $field['name'] ?? '';
                    $value = $field['value'] ?? '';
                    if (!empty($name) || !empty($value)) {
                        $embedParts[] = '**' . $name . '**: ' . $value;
                    }
                }
            }
            if (!empty($embed['footer']['text'])) {
                $embedParts[] = '_' . $embed['footer']['text'] . '_';
            }

            if (!empty($embedParts)) {
                $parts[] = implode("\n", $embedParts);
            }
        }

        return implode("\n\n", $parts);
    }
}