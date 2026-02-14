# Discord Webhooks for Nextcloud Talk

This app allows you to create webhooks that accept Discord-formatted JSON payloads and post them to Nextcloud Talk conversations using **Nextcloud Flow**.

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

## Usage via Nextcloud Flow

1.  Go to **Settings > Flow** (Administration or Personal).
2.  Create a new Flow using the **Discord Webhook** trigger.
3.  **Add a condition** (Optional but recommended):
    *   File name / Token matches `your-secret-string`.
    *   *Note: The "Token" you use in the URL is passed as the subject to the Flow engine.*
4.  **Add an action**:
    *   Select **Send to Talk (Discord Format)**.
    *   Enter the **Talk Conversation Token** (found in the Talk conversation URL).
5.  **Trigger the Webhook**:
    *   Send a POST request to: `https://your-nextcloud.com/index.php/apps/talk_discord_webhook/api/v1/webhook/your-secret-string`

### Example Curl

```bash
curl -X POST -H "Content-Type: application/json" \
     -d '{"content": "Hello via Flow!", "username": "Bot"}' \
     https://your-nextcloud.com/index.php/apps/talk_discord_webhook/api/v1/webhook/your-secret-string
```

## Features

*   **Flow Integration**: Define flexible rules for when a webhook should post to Talk.
*   **Discord Compatibility**: Supports `content` and `embeds`.
*   **Secure**: Use random tokens in your Flow conditions to secure your webhooks.
