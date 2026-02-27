import authRoute from './modules/authRoute';
import { createRouter, createWebHistory } from 'vue-router';
import frontendRoute from './modules/frontendRoute';

const baseRoutes = [
    {
        path: '/',
        redirect: { name: 'frontend.home' },
        name: 'root'
    },

];

const routes = baseRoutes.concat(
    authRoute,
    frontendRoute
);

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
