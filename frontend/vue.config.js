const {
    defineConfig
} = require('@vue/cli-service')

const path = require('path')

module.exports = defineConfig({
    publicPath: process.env.NODE_ENV === 'production' ? process.env.BASE_URL : '/',

    pluginOptions: {
        i18n: {
            locale: 'en',
            fallbackLocale: 'en',
            localeDir: 'locales',
            enableInSFC: false
        }
    },

    configureWebpack: {
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'src'), // alias @ vers /src
                '@store': path.resolve(__dirname, 'src/store') // alias personnalisé facultatif
            }
        }
    },

    transpileDependencies: true
})