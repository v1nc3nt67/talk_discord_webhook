/**
 * Register the Discord Webhook settings panel into Talk's conversation settings
 * via OCA.Talk.Settings.registerSettingPanel().
 */
import WebhookSettings from './views/WebhookSettings.vue'
import MinimalTest from "./views/MinimalTest.vue";

document.addEventListener('DOMContentLoaded', () => {
    if (!window.OCA?.Talk?.Settings?.registerSection) {
        console.debug('[talk_discord_webhook] OCA.Talk.Settings.registerSection not available, skipping')
        return
    }

    window.OCA.Talk.Settings.registerSection({
        id: 'talk-discord-webhook',
        label: 'Discord Webhooks',
        component: MinimalTest,
    })

    console.log('Register section', MinimalTest)
})