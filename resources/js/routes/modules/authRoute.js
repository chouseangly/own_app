import Register from "../../components/frontend/auth/Register.vue";
import HomeComponent from "../../components/frontend/home/HomeComponent.vue";

const authRoute = [
    {
        path:'/register',
        name: 'frontend.register',
        component: Register
    },
    {
        path: '/',
        name: 'frontend.home',
        component: HomeComponent 
    }
]

export default authRoute;
