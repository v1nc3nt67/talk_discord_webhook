<template>
  <div class="webhook-create-form">
    <h5>Create new webhook</h5>

    <div class="webhook-create-form__field">
      <label for="webhook-title">Title</label>
      <input
          id="webhook-title"
          v-model="title"
          type="text"
          placeholder="e.g. CI/CD Notifications"
          :disabled="saving" />
    </div>

    <div class="webhook-create-form__field">
      <label for="webhook-description">Description</label>
      <textarea
          id="webhook-description"
          v-model="description"
          placeholder="Optional description…"
          rows="2"
          :disabled="saving" />
    </div>

    <p v-if="error" class="webhook-create-form__error">
      {{ error }}
    </p>

    <div class="webhook-create-form__actions">
      <button
          class="primary"
          :disabled="saving || !title.trim()"
          @click="onCreate">
        {{ saving ? 'Saving…' : 'Save' }}
      </button>
      <button :disabled="saving" @click="$emit('cancel')">
        Cancel
      </button>
    </div>
  </div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'

export default {
  name: 'WebhookCreateForm',

  props: {
    conversationToken: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      title: '',
      description: '',
      saving: false,
      error: '',
    }
  },

  methods: {
    async onCreate() {
      this.saving = true
      this.error = ''

      try {
        const url = generateUrl('/apps/talk_discord_webhook/api/v1/webhooks')
        const response = await axios.post(url, {
          title: this.title.trim(),
          description: this.description.trim(),
          conversationToken: this.conversationToken,
        })
        this.$emit('created', response.data)
        this.title = ''
        this.description = ''
      } catch (err) {
        console.error('Failed to create webhook', err)
        this.error = err?.response?.data?.error || 'Failed to create webhook'
      } finally {
        this.saving = false
      }
    },
  },
}
</script>

<style scoped>
.webhook-create-form {
  background: var(--color-background-dark);
  border-radius: var(--border-radius-large);
  padding: 12px;
  margin-top: 8px;
}

.webhook-create-form h5 {
  font-weight: bold;
  margin-bottom: 8px;
}

.webhook-create-form__field {
  margin-bottom: 8px;
}

.webhook-create-form__field label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 4px;
}

.webhook-create-form__field input,
.webhook-create-form__field textarea {
  width: 100%;
  padding: 6px 8px;
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius);
  font-size: 13px;
  box-sizing: border-box;
}

.webhook-create-form__error {
  color: var(--color-error);
  font-size: 13px;
  margin-bottom: 8px;
}

.webhook-create-form__actions {
  display: flex;
  gap: 8px;
}

.webhook-create-form__actions button {
  padding: 6px 14px;
  border-radius: var(--border-radius-large);
  border: 1px solid var(--color-border);
  cursor: pointer;
  font-size: 13px;
}

.webhook-create-form__actions button.primary {
  background-color: var(--color-primary);
  color: var(--color-primary-text);
  border-color: var(--color-primary);
}

.webhook-create-form__actions button.primary:hover {
  background-color: var(--color-primary-hover);
}

.webhook-create-form__actions button.primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>