<?php
/** @var array $_ */
/** @var \OCA\TalkDiscordWebhook\Db\Webhook[] $webhooks */
$webhooks = $_['webhooks'];
?>

<div class="section" id="talk-discord-webhook">
    <h2>Discord Webhooks for Talk</h2>
    <p class="settings-hint">Create webhooks to post messages to Talk conversations from external services (Discord format).</p>

    <div id="talk-discord-webhook-list">
        <?php foreach ($webhooks as $webhook): ?>
            <div class="webhook-item" data-id="<?php p($webhook->getId()); ?>" style="display: flex; align-items: center; margin-bottom: 10px;">
                <span style="font-weight: bold; margin-right: 10px;"><?php p($webhook->getName()); ?></span>
                <input type="text" readonly value="<?php p(OC::$server->getURLGenerator()->linkToRouteAbsolute('talk_discord_webhook.webhook.handle', ['token' => $webhook->getToken()])); ?>" style="flex-grow: 1; margin-right: 10px;">
                <button class="icon-delete" title="Delete"></button>
            </div>
        <?php
endforeach; ?>
    </div>

    <h3>Create New Webhook</h3>
    <form id="talk-discord-webhook-form">
        <input type="text" id="webhook-name" placeholder="Webhook Name (e.g. GitHub)" required>
        <input type="text" id="webhook-room-token" placeholder="Talk Conversation Token" required>
        <input type="submit" value="Create Webhook" class="primary">
    </form>
    <p class="settings-hint">You can find the Conversation Token in the URL of the Talk conversation (e.g. /call/<b>token</b>).</p>
</div>
