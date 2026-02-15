<?php

declare(strict_types=1);

namespace OCA\TalkDiscordWebhook\Service;

use OCP\Http\Client\IClientService;
use OCP\IConfig;
use OCP\IUserSession;
use Psr\Log\LoggerInterface;

class TalkService {

    private IClientService $clientService;
    private IConfig $config;
    private LoggerInterface $logger;

    public function __construct(
        IClientService $clientService,
        IConfig $config,
        LoggerInterface $logger
    ) {
        $this->clientService = $clientService;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Send a message to a Nextcloud Talk conversation via the OCS API.
     *
     * @param string $conversationToken The Talk conversation token
     * @param string $message The message content
     * @param string $userId The user ID on whose behalf the message is sent
     * @throws \Exception If the request fails
     */
    public function sendMessage(string $conversationToken, string $message, string $userId): void {
        $baseUrl = $this->config->getSystemValueString('overwrite.cli.url', '');

        if (empty($baseUrl)) {
            // Fallback: try to build from trusted domains
            $trustedDomains = $this->config->getSystemValue('trusted_domains', []);
            if (!empty($trustedDomains)) {
                $baseUrl = 'https://' . $trustedDomains[0];
            }
        }

        if (empty($baseUrl)) {
            throw new \RuntimeException(
                'Cannot determine Nextcloud base URL. Please set overwrite.cli.url in config.php'
            );
        }

        $baseUrl = rtrim($baseUrl, '/');
        $endpoint = $baseUrl . '/ocs/v2.php/apps/spreed/api/v1/chat/' . $conversationToken;

        $client = $this->clientService->newClient();

        $response = $client->post($endpoint, [
            'headers' => [
                'OCS-APIRequest' => 'true',
                'Content-Type'   => 'application/json',
                'Accept'         => 'application/json',
            ],
            'auth' => [$userId, ''],
            'json' => [
                'message' => $message,
            ],
            // Use internal request to bypass auth when running server-side
            'nextcloud' => [
                'allow_local_address' => true,
            ],
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode < 200 || $statusCode >= 300) {
            $this->logger->error('Talk API returned status ' . $statusCode, [
                'app' => 'talk_discord_webhook',
                'response' => $response->getBody(),
            ]);
            throw new \RuntimeException('Talk API returned HTTP ' . $statusCode);
        }
    }
}