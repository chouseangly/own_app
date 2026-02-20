import './bootstrap';
import '../css/app.css';
import Toast from "vue-toastification";
import { createApp } from 'vue'
import DefaultComponent from './DefaultComponent.vue';
import store from './store'
import router from './routes';
import "vue-toastification/dist/index.css";
const toastOptions = {
    timeout: 2000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false,
    // Add transition for a "look good" effect
    transition: "Vue-Toastification__bounce",
    maxToasts: 5,
    newestOnTop: true
};
const app = createApp(DefaultComponent);

app.use(store); // Use the store
app.use(router)
app.use(Toast,toastOptions)
app.mount('#app');
