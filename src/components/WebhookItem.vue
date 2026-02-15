<template>
  <div class="webhook-item">
    <div class="webhook-item__header">
      <strong class="webhook-item__title">{{ webhook.title || 'Untitled Webhook' }}</strong>
      <button
          class="webhook-item__delete"
          title="Delete webhook"
          @click="$emit('delete', webhook.id)">
        ✕
      </button>
    </div>

    <p v-if="webhook.description" class="webhook-item__description">
      {{ webhook.description }}
    </p>

    <div class="webhook-item__url">
      <label>Webhook URL:</label>
      <div class="webhook-item__url-row">
        <input
            ref="urlInput"
            type="text"
            readonly
            :value="webhook.url"
            class="webhook-item__url-input"
            @click="selectUrl" />
        <button class="webhook-item__copy" @click="copyUrl">
          {{ copied ? '✓ Copied' : '📋 Copy' }}
        </button>
      </div>
    </div>

    <p class="webhook-item__date">
      Created: {{ formattedDate }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'WebhookItem',

  props: {
    webhook: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      copied: false,
    }
  },

  computed: {
    formattedDate() {
      if (!this.webhook.createdAt) {
        return 'Unknown'
      }
      try {
        return new Date(this.webhook.createdAt).toLocaleString()
      } catch (e) {
        return this.webhook.createdAt
      }
    },
  },

  methods: {
    selectUrl() {
      this.$refs.urlInput.select()
    },

    async copyUrl() {
      try {
        await navigator.clipboard.writeText(this.webhook.url)
        this.copied = true
        setTimeout(() => {
          this.copied = false
        }, 2000)
      } catch (e) {
        // Fallback: select the input
        this.selectUrl()
      }
    },
  },
}
</script>

<style scoped>
.webhook-item {
  background: var(--color-background-dark);
  border-radius: var(--border-radius-large);
  padding: 12px;
  margin-bottom: 8px;
}

.webhook-item__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 4px;
}

.webhook-item__title {
  font-size: 14px;
}

.webhook-item__delete {
  background: none;
  border: none;
  color: var(--color-error);
  cursor: pointer;
  font-size: 16px;
  padding: 2px 6px;
  border-radius: var(--border-radius);
}

.webhook-item__delete:hover {
  background: var(--color-error);
  color: white;
}

.webhook-item__description {
  color: var(--color-text-maxcontrast);
  font-size: 12px;
  margin-bottom: 8px;
}

.webhook-item__url {
  margin-bottom: 6px;
}

.webhook-item__url label {
  font-size: 12px;
  font-weight: bold;
  display: block;
  margin-bottom: 4px;
}

.webhook-item__url-row {
  display: flex;
  gap: 4px;
}

.webhook-item__url-input {
  flex: 1;
  font-size: 11px;
  padding: 4px 8px;
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius);
  background: var(--color-main-background);
  font-family: monospace;
}

.webhook-item__copy {
  background: var(--color-background-dark);
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius);
  padding: 4px 8px;
  cursor: pointer;
  font-size: 12px;
  white-space: nowrap;
}

.webhook-item__copy:hover {
  background: var(--color-background-hover);
}

.webhook-item__date {
  font-size: 11px;
  color: var(--color-text-maxcontrast);
  margin: 0;
}
</style>