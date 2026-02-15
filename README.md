# Discord Webhooks for Nextcloud Talk

This app allows you to create webhooks that accept Discord-formatted JSON payloads and post them to Nextcloud Talk conversation.

## Installation

1.  Place this directory (`talk_discord_webhook`) into your Nextcloud `apps/` directory.
2.  Enable the app via `occ app:enable talk_discord_webhook`.

## Updating from GitHub

If you installed via Git:

1.  Navigate to the app directory:
    ```bash
    cd /var/www/nextcloud/apps/talk_discord_webhook
    ```
2.  Pull the latest changes:
    ```bash
    git pull origin main
    ```

If you installed manually via ZIP:

1.  Download the latest release ZIP.
2.  Replace the existing `talk_discord_webhook` folder in `apps/`.

## Usage via Talk Conversation

1.  Go to **Talk > Conversation settings**.
2.  Go to the **Webhook** section.
3.  Click on create webhook.
4.  Fill the title and description fields, then save.
5.  Copy the webhook url
6.  **Trigger the Webhook**:
    *   Send a POST request to: `https://your-nextcloud.com/index.php/apps/talk_discord_webhook/api/v1/webhook/your-secret-string`

### Example Curl

```bash
curl -X POST -H "Content-Type: application/json" \
     -d '{"content": "Hello via Flow!", "username": "Bot"}' \
     https://your-nextcloud.com/index.php/apps/talk_discord_webhook/api/v1/webhook/your-secret-string
```