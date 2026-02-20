<template>
    <LoadingComponent :props="loading" />
    <div class="w-full max-w-3xl mx-auto rounded-3xl flex overflow-hidden shadow-lg bg-white mb-24 sm:mb-0">
        <div class="hidden sm:block sm:w-1/2">
            <img src="/images/required/auth.jpg" alt="auth" class="w-full h-full object-cover rounded-l-3xl">
        </div>

        <form class="w-full sm:w-1/2 p-8 sm:p-10 flex flex-col justify-center" @submit.prevent="submitReset">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-sky-600 mb-2">New Password</h2>
                <p class="text-gray-400 text-sm">Set your new secure password.</p>
            </div>

            <div class="mb-6">
                <input v-model="form.password" :class="errors.password ? 'border-red-500' : 'border-gray-300'"
                    type="password" placeholder="New Password"
                    class="w-full h-14 px-5 rounded-xl border focus:border-sky-500 focus:outline-none transition-all duration-300">
                <small class="text-red-500 mt-1 block" v-if="errors.password">{{ errors.password[0] }}</small>
            </div>

            <div class="mb-6">
                <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password"
                    class="w-full h-14 px-5 rounded-xl border border-gray-300 focus:border-sky-500 focus:outline-none transition-all duration-300">
            </div>

            <button type="submit"
                class="w-full h-14 rounded-xl bg-gradient-to-r from-sky-400 to-sky-600 text-white font-semibold text-lg hover:from-sky-500 transition-all duration-300">
                Reset Password
            </button>
        </form>
    </div>
</template>

<script>
import { useToast } from 'vue-toastification';
import LoadingComponent from '../../admin/components/LoadingComponent.vue';

export default {
    name: 'ResetPassword',
    components: { LoadingComponent },
    setup() {
        const toast = useToast();
        return { toast };
    },
    data() {
        return {
            loading: { isActive: false },
            form: {
                email: this.$route.query.email || "",
                token: this.$route.query.token || "", // This is the verified OTP
                password: "",
                password_confirmation: ""
            },
            errors: {}
        }
    },
    methods: {
        async submitReset() {
            try {
                this.loading.isActive = true;
                this.errors = {};
                await this.$store.dispatch('auth/resetPassword', this.form);
                this.toast.success('Password reset successful! Please login.');
                this.$router.push({ name: 'frontend.login' });
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
                this.toast.error('Failed to reset password.');
            } finally {
                this.loading.isActive = false;
            }
        }
    }
}
</script>
