<template>
    <LoadingComponent :props="loading" />

    <div class="w-full max-w-3xl mx-auto rounded-3xl flex overflow-hidden shadow-lg bg-white mb-24 sm:mb-0">

        <!-- Left image (hidden on mobile) -->
        <div class="hidden sm:block sm:w-1/2">
            <img src="images/required/auth.jpg" alt="auth" class="w-full h-full object-cover rounded-l-3xl"
                loading="lazy">
        </div>

        <!-- OTP form -->
        <form class="w-full sm:w-1/2 p-8 sm:p-10 flex flex-col justify-center" @submit.prevent="submitOtp">

            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-sky-600 mb-2">Verify OTP</h2>
                <p class="text-gray-400 text-sm">Enter the 6-digit code sent to your email</p>
            </div>

            <!-- OTP input -->
            <div class="mb-6">
                <input v-model="form.otp"
                    :class="errors.otp ? 'border-red-500' : 'border-gray-300 focus:border-sky-500'" type="text"
                    placeholder="Enter 6-digit code"
                    class="w-full h-14 px-5 rounded-xl text-base border hover:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200 transition-all duration-300">
                <small class="text-red-500 mt-1 block" v-if="errors.otp">{{ errors.otp[0] }}</small>
            </div>

            <!-- Submit button -->
            <button type="submit"
                class="w-full h-14 rounded-xl bg-gradient-to-r from-sky-400 to-sky-600 text-white font-semibold text-lg shadow-md hover:shadow-lg hover:from-sky-500 hover:to-sky-700 transition-all duration-300 mb-4">
                Verify Code
            </button>

            <!-- Resend OTP -->
            <div class="text-center">
                <button type="button" @click="resendOtp"
                    class="text-sky-500 font-medium hover:underline hover:text-sky-600 transition-colors duration-300">
                    Resend Code
                </button>
            </div>

        </form>
    </div>

</template>
<script>
import { useToast } from 'vue-toastification';
import LoadingComponent from '../../admin/components/LoadingComponent.vue';

export default {
    name: 'VerifyOtp',
    components: {
        LoadingComponent
    },
    setup() {
        const toast = useToast();
        return { toast };
    }
    ,
    data() {
        return {
            loading: {
                isActive: false
            },
            form: {
                otp: '',
                email: this.$route.query.email || '' // Retrieve email from URL if passed
            },
            errors: {}
        }
    },
    methods: {
        async submitOtp() {
            try {
                this.loading.isActive = true;
                this.errors = {};

                await this.$store.dispatch('/auth/verifyOtp', this.form);
                this.toast.success('Verify otp successfully')
                this.$router.push({ name: 'frontend.resetPassword' });

            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                    this.toast.error('Invalid OTP code.')

                } else {
                    this.toast.error("Something went wrong.");
                }
            } finally {
                this.loading.isActive = false;
            }

        },
        async resendOtp(){
            try{
                this.loading.isActive = true;
            this.errors = {};
            await this.$store.dispatch('auth/resentOtp',this.form);
           this.$router.push({ name: 'frontend.verifyOtp', query: { email: this.form.email } });
            this.toast.success('Resend OTP code Successfully');
            }catch(error){
                this.toast.error('Fail to resent otp.')
            }finally {
                this.loading.isActive = false;
            }

        }
    }
}
</script>
