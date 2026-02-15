<template>
  <div class="webhook-settings">
    <h4 class="webhook-settings__title">
      🔗 Discord Webhooks
    </h4>
  </div>
</template>

<script>
export default {
  name: 'MinimalTest',

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
</style>