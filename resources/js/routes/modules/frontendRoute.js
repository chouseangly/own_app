
import HomeComponent from "../../components/frontend/home/HomeComponent.vue";

const frontendRoute = [
    {
        path: '/home', // You must add this path
        name: 'frontend.home', // This matches the redirect in index.js
        component: HomeComponent
    },
    {
        path:'/products',
        name: 'frontend.product'
    }
]
export default frontendRoute;
