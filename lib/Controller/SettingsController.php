<?php

declare(strict_types = 1)
;

namespace OCA\TalkDiscordWebhook\Controller;

use OCA\TalkDiscordWebhook\Db\Webhook;
use OCA\TalkDiscordWebhook\Db\WebhookMapper;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\IUserSession;
use OCP\Util;

class SettingsController extends Controller
{
    private WebhookMapper $mapper;
    private IUserSession $userSession;

    public function __construct(string $appName, IRequest $request, WebhookMapper $mapper, IUserSession $userSession)
    {
        parent::__construct($appName, $request);
        $this->mapper = $mapper;
        $this->userSession = $userSession;
    }

    /**
     * @NoAdminRequired
     */
    public function index(): TemplateResponse
    {
        Util::addScript('talk_discord_webhook', 'settings');
        // We would pass existing webhooks here
        $userId = $this->userSession->getUser()->getUID();
        $webhooks = $this->mapper->findAllByUser($userId);

        return new TemplateResponse('talk_discord_webhook', 'settings/personal', ['webhooks' => $webhooks]);
    }

    /**
     * @NoAdminRequired
     */
    public function create(string $name, string $roomToken): JSONResponse
    {
        $userId = $this->userSession->getUser()->getUID();

        $webhook = new Webhook();
        $webhook->setToken(bin2hex(random_bytes(32)));
        $webhook->setRoomToken($roomToken);
        $webhook->setName($name);
        $webhook->setUserId($userId);

        $this->mapper->insert($webhook);

        return new JSONResponse($webhook->jsonSerialize());
    }

    /**
     * @NoAdminRequired
     */
    public function destroy(int $id): JSONResponse
    {
        $userId = $this->userSession->getUser()->getUID();
        try {
            $webhook = $this->mapper->find($id); // Assuming standard find method allows retrieving by ID
            // Check ownership
            if ($webhook->getUserId() !== $userId) {
                return new JSONResponse(['error' => 'Forbidden'], 403);
            }
            $this->mapper->delete($webhook);
        }
        catch (\Exception $e) {
            return new JSONResponse(['error' => 'Not found'], 404);
        }

        return new JSONResponse(['success' => true]);
    }
}
