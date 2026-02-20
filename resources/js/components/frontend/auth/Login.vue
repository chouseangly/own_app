<template >
    <LoadingComponent :props="loading"/>
    <div class="w-full max-w-3xl mx-auto rounded-2xl flex overflow-hidden gap-y-6 bg-white shadow-card mb-24 !sm:mb-0">
        <img src="images/required/auth.jpg" class="w-full hidden sm:block sm:max-w-xs md:max-w-sm flex-shrink-0" loading="lazy" alt="auth">

        <form class="w-full p-6" @submit.prevent="submitLogin">
            <div class="text-center mb-8">
                <h2 class="capitalize text-2xl mb-2 font-bold text-primary">Login</h2>
            </div>

            <div class="mb-6">
                <label for="formEmail" class="capitalize text-sm mb-1 field-title font-medium required">email</label>
                <input v-model="form.email" :class="errors.email ? 'border-red-500' : ''" id="formEmail" type="text"
                    class="w-full h-12 px-4 rounded-lg text-base border border-[#D9DBE9] hover:border-sky-400 focus-within:border-sky-400 transition-all duration-500">
                <small class="text-red-500" v-if="errors.email">{{ errors.email[0] }}</small>
            </div>

            <div class="mb-6">
                <label for="formPassword" class="capitalize text-sm mb-1 field-title font-medium required">password</label>
                <input v-model="form.password" :class="errors.password ? 'border-red-500' : ''" id="formPassword" type="password"
                    class="w-full h-12 px-4 rounded-lg text-base border border-[#D9DBE9] hover:border-sky-400 focus-within:border-sky-400 transition-all duration-500">
                <small class="text-red-500" v-if="errors.password">{{ errors.password[0] }}</small>
            </div>
                <div class="mb-6 text-end">
                    <router-link class="capitalize font-normal  " :to="{ name: 'frontend.forgotPassword' }">
                    forgotPassword
                </router-link>
                </div>


            <button type="submit"
                class="font-bold text-center w-full h-12 leading-12 rounded-full bg-sky-400 text-white capitalize mb-6">
               Login
            </button>

        </form>
    </div>
</template>
<script>
import { useToast } from 'vue-toastification';
import LoadingComponent from '../../admin/components/LoadingComponent.vue';

export default {
    name: 'Login',
    components:{
        LoadingComponent
    },
    setup(){
        const toast = useToast()
        return {toast};
    },
    data(){
        return {
            loading:{
                isActive: false
            },
            form:{
                email: '',
                password: ''
            },
            errors: {}
        }
    },
    methods:{
        async submitLogin(){
            try{
                this.loading.isActive = true;
                this.errors = {};
                await this.$store.dispatch('auth/login',this.form)
                this.toast.success('Login successfully')
                this.$router.push({name:'frontend.home'});

            }catch(error){
                this.toast.error('something went wrong.')
            }finally{
                this.loading.isActive = false;
            }
        }
    }

}
</script>

