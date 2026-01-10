import { createApp } from 'vue'
import 'vue-toastification/dist/index.css'
import Toast from 'vue-toastification'

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
app.use(Toast, {
  position: 'top-right',
  timeout: 3000,
  closeOnClick: true,
  pauseOnHover: true,
})
app.mount('#app')
