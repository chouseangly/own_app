import './bootstrap';
import { createApp } from 'vue'
import DefaultComponent from './DefaultComponent.vue';
import store from './store'



const app = createApp(DefaultComponent);

app.use(store); // Use the store
app.mount('#app');
