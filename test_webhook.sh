#!/bin/bash

# Configuration
NC_URL="http://localhost/nextcloud" # Change this
WEBHOOK_TOKEN="my-secret-token" # Match this in your Flow configuration

echo "Pre-requisite: Configure a Flow in Nextcloud:"
echo "  Trigger: Discord Webhook"
echo "  Condition: Token matches '$WEBHOOK_TOKEN'"
echo "  Action: Send to Talk (Discord Format) -> [Your Room Token]"
echo ""

# Simple Message
echo "Sending simple message..."
curl -X POST "$NC_URL/index.php/apps/talk_discord_webhook/api/v1/webhook/$WEBHOOK_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
           "content": "Hello World from Shell Script!"
         }'
echo -e "\n"

# Rich Embed Message
echo "Sending embed message..."
curl -X POST "$NC_URL/index.php/apps/talk_discord_webhook/api/v1/webhook/$WEBHOOK_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
           "embeds": [
             {
               "title": "Alert: Server Down",
               "description": "The main server is not responding.",
               "color": 15158332,
               "fields": [
                 {
                   "name": "Server ID",
                   "value": "srv-01",
                   "inline": true
                 },
                 {
                   "name": "Status",
                   "value": "CRITICAL",
                   "inline": true
                 }
               ]
             }
           ]
         }'
echo -e "\n"
