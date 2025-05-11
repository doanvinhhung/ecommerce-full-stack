import 'vue-image-zoomer/dist/style.css';
import VueImageZoomer from 'vue-image-zoomer';
import 'vue-loading-overlay/dist/css/index.css';
import VueDOMPurifyHTML from 'vue-dompurify-html';
import Toast from "vue-toastification";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import "vue-toastification/dist/index.css";

import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from 'pinia';
import axios from 'axios';
import { useAuthStore } from '@/stores/useAuthStore'; // Thêm dòng này

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

const app = createApp(App);

app.use(router);
app.use(Toast);
app.use(VueImageZoomer);
app.use(pinia);
app.use(VueDOMPurifyHTML);



const authStore = useAuthStore();
authStore.initAuthInterceptor(router);  // Pass router instance

// Load initial auth state if token exists
if (authStore.accessToken) {
  authStore.fetchCurrentUser().catch(() => {
    authStore.clearAuthData();
  });
}
// Thiết lập interceptor trước khi mount app



app.mount("#app");