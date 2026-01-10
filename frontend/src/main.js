import { createApp } from 'vue'

import { createPinia } from "pinia";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);



import './style.css'
import App from '@/App.vue'
import router from '@/router'
const app = createApp(App)
app.use(router)
app.use(pinia)
app.mount('#app')
