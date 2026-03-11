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
    getters: {
        authStatus: (state) => !!state.token,
        authInfo: (state) => state.user,
        authDefaultPermission: (state) => state.user?.permissions || {},
        authDefaultMenu: (state) => state.user?.default_menu || null,
    },
    mutations: {
        AUTH_SUCCESS(state, { token, user }) {
            state.status = 'success';
            state.token = token;
            state.user = user;
        },
        AUTH_ERROR(state) {
            state.status = 'error';
            state.user = null; // Reset user on error
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
                const response = await axios.post('/api/auth/register', userData);
                return response.data;

            } catch (error) {
                commit('AUTH_ERROR');
                throw error;
            }
        },
        async verifyOtp({ commit }, otpData) {
            try {
                const response = await axios.post('/api/auth/verify-otp', otpData);
                return response.data;
            } catch (error) {

                throw error;
            }
        },
       async login({ commit }, credentials) {
            try {
                const response = await axios.post('/api/auth/login', credentials);
                const token = response.data.token;
                const user = response.data.user;

                localStorage.setItem('token', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                commit('AUTH_SUCCESS', { token, user });
                return response.data;
            } catch (error) {
                localStorage.removeItem('token');
                commit('AUTH_ERROR');
                throw error;
            }
        },
        },
        async resentOtp({ commit }, emailData) {
            try {
                const response = await axios.post('/api/auth/resent-otp', emailData);
                return response.data;
            } catch (error) {
                throw error
            }
        },
        async forgotPassword({ commit }, emailData) {
            try {
                const response = await axios.post('/api/auth/forgot-password', emailData)
                return response.data;

            } catch (error) {
                throw error
            }
        },
        async updateUser({ commit }) {
            const token = localStorage.getItem('token');
            if (token) {
                try {
                    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    const response = await axios.get('/api/profile');
                    // Ensure this matches your backend response (e.g., response.data.data or just response.data)
                    const user = response.data.data || response.data;
                    commit('AUTH_SUCCESS', { token, user });
                } catch (error) {
                    localStorage.removeItem('token');
                    commit('LOGOUT');
                }
            } else {
                commit('LOGOUT');
            }
        },
        async logout({ commit }) {
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
            commit('LOGOUT');
        },
        async resetPassword({ commit }, payload) {
            try {
                const response = await axios.post('/api/auth/reset-password', payload);
                return response.data;
            } catch (error) {
                throw error;
            }
        },

        async getMe({ commit }) {
            try {
                const response = await axios.get('/api/auth/me');
                commit('AUTH_SUCCESS', { token: localStorage.getItem('token'), user: response.data.data });
            } catch (error) {
                commit('LOGOUT');
            }
        }
    }

