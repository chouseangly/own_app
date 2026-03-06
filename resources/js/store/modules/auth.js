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
    getters: { // <--- Add this block
        authStatus: (state) => !!state.token,
        authInfo: (state) => state.user,
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
                const response = await axios.post('/api/login', credentials);
                // Fix: Extract directly from response.data based on your JSON structure
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
        async updateUser({ commit }) {
           try {
                if (localStorage.getItem('token')) {
                    const response = await axios.get('/api/profile');
                    // Fix: If your profile API matches login structure, use response.data.user
                    // If it returns only user data, use response.data.data
                    const user = response.data.user || response.data.data;
                    commit('AUTH_SUCCESS', {
                        token: localStorage.getItem('token'),
                        user: user
                    });
                }
            } catch (error) {
                localStorage.removeItem('token');
                commit('LOGOUT');
            }
        },
        async resetPassword({ commit }, payload) {
            try {
                const response = await axios.post('/api/reset-password', payload);
                return response.data;
            } catch (error) {
                throw error;
            }
        },

        async logout({ commit }) {
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
            commit('LOGOUT');
        },

        async getMe({ commit }) {
            try {
                const response = await axios.get('/api/me');
                commit('AUTH_SUCCESS', { token: localStorage.getItem('token'), user: response.data.data });
            } catch (error) {
                commit('LOGOUT');
            }
        }
    }
}
