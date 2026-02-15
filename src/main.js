/**
 * Register the Discord Webhook settings panel into Talk's conversation settings
 * via OCA.Talk.Settings.registerSettingPanel().
 */
import { subscribe } from '@nextcloud/event-bus'
import * as NextcloudEventBus from '@nextcloud/event-bus';

// Save the original emit function
const originalEmit = NextcloudEventBus.emit;

// Override the emit function with custom logging
NextcloudEventBus.emit = (type, data) => {
    console.log(`Event Fired: ${type}`, data);
    // Call the original emit function
    originalEmit(type, data);
};

subscribe('spreed:conversation:open', (data) => {
    console.log('Conversation opened:', data);
    // Logic to inject your settings or UI elements here
})
subscribe('spreed:conversation:update', (data) => {
    console.log('Conversation updated:', data);
    // Logic to inject your settings or UI elements here
})
subscribe('spreed:sidebar:tab:change', (data) => {
    console.log('Conversation change:', data);
    // Logic to inject your settings or UI elements here
})

console.log(NextcloudEventBus);
//
// document.addEventListener('DOMContentLoaded', () => {
//     if (!window.OCA?.Talk?.Settings?.registerSection) {
//         console.debug('[talk_discord_webhook] OCA.Talk.Settings.registerSection not available, skipping')
//         return
//     }
//
//     window.OCA.Talk.Settings.registerSection({
//         id: 'talk-discord-webhook',
//         label: 'Discord Webhooks',
//         component: MinimalTest,
//     })
//
//     console.log('Register section', MinimalTest)
// })