import axios from "axios";
import { useToast } from "vue-toastification";
const toast = useToast();

export const auth = {
    namespaced: true,
    state: {
        user: null,
        token: localStorage.getItem('token') || null,
        status: ''
    },
    mutations: {
        AUTH_SUCCESS(state, { token, user }) {
            state.status = 'success';
            state.token = token;
            state.user = user;
        },

        AUTH_ERROR(state) {
            state.status = 'error';
        },

        LOGOUT(state) {
            state.status = '';
            state.token = null;
            state.user = null;
        }


    },
    actions: {
        async register({ commit }, userData) {
            try {
                const response = await axios.post('/api/register', userData);
                return response.data;

            } catch (error) {
                commit('AUTH_ERROR');
                throw error;
            }
        },
        async verifyOtp({ commit }, otpData) {
            try {
                const response = await axios.post('/api/verify-otp', otpData);
                return response.data;
            } catch (error) {

                throw error;
            }
        },
        async login({ commit }, credentails) {
            try {
                const response = await axios.post('/api/login', credentails);
                const { token, user } = response.data;
                localStorage.setItem('token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                commit('AUTH_SUCCESS', { token, user });
                toast.success("Welcome back!");
                return response.data;
            } catch (error) {
                localStorage.removeItem('token');
                commit('AUTH_ERROR');
                toast.error("Invalid credentials.");
                throw error;
            }
        },
        async resentOtp({ commit }, emailData) {
            try {
                const response = await axios.post('/api/resent-otp', emailData);
                return response.data;
            } catch (error) {
                throw error
            }
        },
        async forgotPassword({ commit }, emailData) {
            try {
                const response = await axios.post('/api/forgotPassword', emailData)
                return response.data;

            } catch (error) {
                throw error
            }
        },
        async resetPassword({ commit }, payload) {
            try {
                const response = await axios.post('/api/reset-password', payload);
                return response.data;
            } catch (error) {
                throw error;
            }
        }
    }
}
