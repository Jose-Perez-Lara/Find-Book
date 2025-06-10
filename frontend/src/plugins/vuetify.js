/**
 * plugins/vuetify.js
 *
 * Framework documentation: https://vuetifyjs.com`
 */

// Styles
import '@mdi/font/css/materialdesignicons.css'
// Composables
import { createVuetify } from 'vuetify'
import { VCalendar } from 'vuetify/labs/VCalendar'
import { es } from 'vuetify/locale'

export default createVuetify({
  components: {
    VCalendar,
  },
  locale: {
    locale: 'es',
    messages: { es },
  },
  theme: {
    defaultTheme: 'light',
  },
})

