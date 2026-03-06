import authRoute from './modules/authRoute';
import { createRouter, createWebHistory } from 'vue-router';
import frontendRoute from './modules/frontendRoute';
import profileRoute from './modules/profileRoute';

const baseRoutes = [
    {
        path: '/',
        redirect: { name: 'frontend.home' },
        name: 'root'
    },

];

const routes = baseRoutes.concat(
    authRoute,
    frontendRoute,
    profileRoute
);

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
