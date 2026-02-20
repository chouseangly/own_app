import ForgotPassword from "../../components/frontend/auth/ForgotPassword.vue";
import Login from "../../components/frontend/auth/Login.vue";
import Register from "../../components/frontend/auth/Register.vue";
import VerifyOtp from "../../components/frontend/auth/VerifyOtp.vue";
import HomeComponent from "../../components/frontend/home/HomeComponent.vue";

const authRoute = [
    {
        path: '/register',
        name: 'frontend.register',
        component: Register
    },
    {
        path:'/verify-otp',
        name:'frontend.verifyOtp',
        component: VerifyOtp
    }
    ,
    {
        path: '/login',
        name: 'frontend.login',
        component:Login
    },
    {
        path: '/forgot-password',
        name: 'frontend.forgotPassword',
        component:ForgotPassword
    },
    {
        path: '/reset-passwrod',
        name: 'frontend.resetPassword',
      
    },
    {
        path: '/home', // You must add this path
        name: 'frontend.home', // This matches the redirect in index.js
        component: HomeComponent
    }
];

export default authRoute;
