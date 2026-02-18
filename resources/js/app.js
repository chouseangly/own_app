import './bootstrap';
import { createApp } from 'vue'
import DefaultComponent from './DefaultComponent.vue';
import store from './store'
import router from './routes';



const app = createApp(DefaultComponent);

app.use(store); // Use the store
app.use(router)
app.mount('#app');
