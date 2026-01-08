import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import App from './App.vue';
const pinia = createPinia()
import router from './router';
const app = createApp(App)
app.use(pinia)
app.use(router)
pinia.use(piniaPluginPersistedstate)
app.mount('#app');
