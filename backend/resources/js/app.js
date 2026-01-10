import './bootstrap';
import 'vue-toastification/dist/index.css';

import { createApp } from 'vue';
import { createPinia } from "pinia";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import Toast from 'vue-toastification';
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

import { CkeditorPlugin } from '@ckeditor/ckeditor5-vue';

import App from './App.vue';
import router from './router';
const app = createApp(App);
app.use(pinia);
app.use(router);
app.use(CkeditorPlugin);
app.use(Toast, {
    position: 'top-right',
    timeout: 3000,
    closeOnClick: true,
    pauseOnHover: true,
});
app.mount('#app');
