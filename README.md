# Discord Webhooks for Nextcloud Talk

This app allows you to create webhooks that accept Discord-formatted JSON payloads and post them to Nextcloud Talk conversations.

## Installation

1.  Place this directory (`talk_discord_webhook`) into your Nextcloud `apps/` directory.
2.  Enable the app via `occ app:enable talk_discord_webhook` or the Apps settings page.

## Usage

1.  Go to **Personal Settings** in Nextcloud.
2.  Find the **Discord Webhooks** section.
3.  Enter a **Name** for your webhook (e.g., "GitHub Actions").
4.  Enter the **Talk Conversation Token**.
    *   You can find this token in the URL of the Talk conversation: `https://cloud.example.com/call/TOKEN`
5.  Click **Create Webhook**.
6.  Copy the generated Webhook URL.

## Sending Messages

You can use this Webhook URL with any service that supports Discord Webhooks (e.g., GitHub, GitLab, Slack-compatible tools).

### Example Curl

```bash
curl -X POST -H "Content-Type: application/json" \
     -d '{"content": "Hello from Curl!", "username": "Bot"}' \
     https://your-nextcloud.com/index.php/apps/talk_discord_webhook/api/v1/webhook/YOUR_WEBHOOK_TOKEN
```

## Features

*   Supports `content` (text messages).
*   Supports `embeds` (rich text with titles, descriptions, fields).
*   Maps to specific Talk conversations.
