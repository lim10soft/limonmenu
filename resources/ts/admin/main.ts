import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import adminI18n from './i18n'
import '../../css/app.css'

const app = createApp(App)
app.use(createPinia())
app.use(router)
app.use(adminI18n)
app.mount('#admin-app')
