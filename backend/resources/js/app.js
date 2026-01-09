import './bootstrap';
import 'vue-toastification/dist/index.css';

import { createApp } from 'vue';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { createPinia } from "pinia";
import Toast from 'vue-toastification';
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

import App from './App.vue';
import router from './router';
const app = createApp(App);
app.use(pinia);
app.use(router);
app.use(Toast, {
    position: 'top-right',
    timeout: 3000,
    closeOnClick: true,
    pauseOnHover: true,
});
app.mount('#app');
