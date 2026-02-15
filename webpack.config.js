const path = require('path')
const webpackConfig = require('@nextcloud/webpack-vue-config')

webpackConfig.entry = {
    talk_discord_webhook: path.join(__dirname, 'src', 'main.js'),
}

module.exports = webpackConfig