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
        $payload = $this->request->getParams();

        // Trigger the Flow Engine
        // We pass the payload as the subject so checks can inspect it if needed
        // $event = new IEntityEvent(
        //     'talk_discord_webhook',
        //     'received',
        //     $payload // Subject
        // );

        // We might want to pass the token as context or part of the subject to allow filtering by token
        // Let's add the token to the payload if not present, or wrap it
        // $data = [
        //     'token' => $token,
        //     'payload' => $payload
        // ];

        // Dispatch the event
        // Note: The Entity implementation is responsible for dispatching or the Controller dispatches to the Manager.
        // Actually, we usually use the IEventDispatcher to dispatch the event that the Entity listens to?
        // Or we use the WorkflowEngine Manager to check rules.

        // Correct usage often involves:
        // $this->manager->triggerEvent('talk_discord_webhook', 'received', $subject);
        // But since we are creating the entity, we can just dispatch the valid event.

        // Let's assume we need to dispatch a generic event that the Flow engine picks up 
        // OR we use the Check logic. 

        // Simple approach: Dispatch an event that our Entity knows about.
        // But wait, the Entity definition `getEvents` maps keys to names.
        // We need to trigger the Flow check.

        // Usage:
        /** @var \OCP\WorkflowEngine\IManager $manager */
        $manager = \OC::$server->get(\OCP\WorkflowEngine\IManager::class);
        $manager->run('talk_discord_webhook', 'received', $token, $payload); // Subject=Token? 

        return new JSONResponse(['success' => true]);
    }
}
