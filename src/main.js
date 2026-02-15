/**
 * Register the Discord Webhook settings panel into Talk's conversation settings
 * via OCA.Talk.Settings.registerSettingPanel().
 */
import WebhookSettings from './views/WebhookSettings.vue'
import MinimalTest from "./views/MinimalTest.vue";
import { subscribe } from '@nextcloud/event-bus'

subscribe('spreed:conversation:open', (data) => {
    console.log('Conversation opened:', data.token);
    // Logic to inject your settings or UI elements here
})

console.log(subscribe);

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