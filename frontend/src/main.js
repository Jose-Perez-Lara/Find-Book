/**
 * main.js
 *
 * Bootstraps Vuetify and other plugins then mounts the App`
 */

// Plugins
import { registerPlugins } from '@/plugins'

// Components
import App from './App.vue'

// Composables
import { createApp } from 'vue'

const app = createApp(App)

registerPlugins(app)

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { es } from 'vuetify/locale'

export default createVuetify({
  locale: {
    locale: 'es',
    messages: { es },
  },
})

app.mount('#app')
