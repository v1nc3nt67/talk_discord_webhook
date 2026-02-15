<template>
  <div class="webhook-settings">
    <h4 class="webhook-settings__title">
      🔗 Discord Webhooks
    </h4>

    <p class="webhook-settings__description">
      Create webhooks that accept Discord-formatted JSON payloads
      and post messages into this conversation.
    </p>

    <!-- Webhook list -->
    <div v-if="loading" class="webhook-settings__loading">
      Loading webhooks…
    </div>

    <div v-else-if="webhooks.length === 0" class="webhook-settings__empty">
      No webhooks configured yet.
    </div>

    <div v-else class="webhook-settings__list">
      <WebhookItem
          v-for="webhook in webhooks"
          :key="webhook.id"
          :webhook="webhook"
          @delete="onDelete" />
    </div>

    <!-- Create form toggle -->
    <div v-if="!showCreateForm" class="webhook-settings__actions">
      <button class="primary" @click="showCreateForm = true">
        + Create webhook
      </button>
    </div>

    <WebhookCreateForm
        v-if="showCreateForm"
        :conversation-token="conversationToken"
        @created="onCreated"
        @cancel="showCreateForm = false" />
  </div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import WebhookItem from '../components/WebhookItem.vue'
import WebhookCreateForm from '../components/WebhookCreateForm.vue'

export default {
  name: 'WebhookSettings',

  components: {
    WebhookItem,
    WebhookCreateForm,
  },

  props: {
    conversationToken: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      webhooks: [],
      loading: true,
      showCreateForm: false,
    }
  },

  mounted() {
    this.fetchWebhooks();
    console.log('Mounted')
  },

  methods: {
    async fetchWebhooks() {
      this.loading = true
      try {
        const url = generateUrl('/apps/talk_discord_webhook/api/v1/webhooks/conversation/{conversationToken}', {
          conversationToken: this.conversationToken,
        })
        const response = await axios.get(url)
        this.webhooks = response.data
      } catch (error) {
        console.error('Failed to load webhooks', error)
        this.webhooks = []
      } finally {
        this.loading = false
      }
    },

    onCreated(webhook) {
      this.webhooks.unshift(webhook)
      this.showCreateForm = false
    },

    async onDelete(webhookId) {
      try {
        const url = generateUrl('/apps/talk_discord_webhook/api/v1/webhooks/{id}', {
          id: webhookId,
        })
        await axios.delete(url)
        this.webhooks = this.webhooks.filter(w => w.id !== webhookId)
      } catch (error) {
        console.error('Failed to delete webhook', error)
      }
    },
  },
}
</script>

<style scoped>
.webhook-settings {
  padding: 16px;
  border-top: 1px solid var(--color-border);
  margin-top: 12px;
}

.webhook-settings__title {
  font-weight: bold;
  font-size: 16px;
  margin-bottom: 8px;
}

.webhook-settings__description {
  color: var(--color-text-maxcontrast);
  font-size: 13px;
  margin-bottom: 12px;
}

.webhook-settings__loading,
.webhook-settings__empty {
  color: var(--color-text-maxcontrast);
  font-style: italic;
  padding: 8px 0;
}

.webhook-settings__list {
  margin-bottom: 12px;
}

.webhook-settings__actions {
  margin-top: 8px;
}

.webhook-settings__actions button.primary {
  background-color: var(--color-primary);
  color: var(--color-primary-text);
  border: none;
  border-radius: var(--border-radius-large);
  padding: 8px 16px;
  cursor: pointer;
  font-size: 14px;
}

.webhook-settings__actions button.primary:hover {
  background-color: var(--color-primary-hover);
}
</style>