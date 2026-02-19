import authRoute from './modules/authRoute';
import { createRouter, createWebHistory } from 'vue-router';

const baseRoutes = [
    {
        path: '/',
        redirect: { name: 'frontend.home' },
        name: 'root'
    }
];

const routes = baseRoutes.concat(authRoute);

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
