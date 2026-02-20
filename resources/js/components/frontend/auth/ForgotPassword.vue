<template>
    <LoadingComponent :props="loading" />
    <div class="w-full max-w-3xl mx-auto rounded-3xl flex overflow-hidden shadow-lg bg-white mb-24 sm:mb-0">

        <!-- Left image (hidden on mobile) -->
        <div class="hidden sm:block sm:w-1/2">
            <img src="images/required/auth.jpg" alt="auth" class="w-full h-full object-cover rounded-l-3xl"
                loading="lazy">
        </div>

        <!-- OTP form -->
        <form class="w-full sm:w-1/2 p-8 sm:p-10 flex flex-col justify-center" @submit.prevent="submitEmail">

            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-sky-600 mb-2">Forgot Password</h2>
                <p class="text-gray-400 text-sm">Enter your email to get OTP code.</p>
            </div>


            <div class="mb-6">
                <input v-model="form.email"
                    :class="errors.email ? 'border-red-500' : 'border-gray-300 focus:border-sky-500'" type="text"
                    placeholder="Enter your email"
                    class="w-full h-14 px-5 rounded-xl text-base border hover:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200 transition-all duration-300">
                <small class="text-red-500 mt-1 block" v-if="errors.email">{{ errors.email[0] }}</small>
            </div>

            <!-- Submit button -->
            <button type="submit"
                class="w-full h-14 rounded-xl bg-gradient-to-r from-sky-400 to-sky-600 text-white font-semibold text-lg shadow-md hover:shadow-lg hover:from-sky-500 hover:to-sky-700 transition-all duration-300 mb-4">
                Send email
            </button>

        </form>
    </div>
</template>
<script>
import { useToast } from 'vue-toastification';
import LoadingComponent from '../../admin/components/LoadingComponent.vue';

export default {
    name: 'ForgotPassword',
    components: {
        LoadingComponent
    },
    setup() {
        const toast = useToast()
        return { toast };
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            form: {
                email: ''
            },
            errors: {}
        }
    },
    methods: {
        async submitEmail() {
            try {
                this.loading.isActive = true;
                this.errors = {};

                await this.$store.dispatch('auth/forgotPassword', this.form)
                this.$router.push({ name: 'frontend.verifyOtp', query: { email: this.form.email } });
                this.toast.success('send email successfully')
            } catch (error) {
                this.toast.error('Something went wrong.')
            } finally {
                this.loading.isActive = false
            }
        }
    }
}
</script>
