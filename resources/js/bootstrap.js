import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Set Base URL from Vite environment variables
axios.defaults.baseURL = import.meta.env.VITE_API_URL ;

axios.interceptors.request.use(
    config => {
        // 1. Set the mandatory API Key for your ApiKeyMiddleware
        config.headers['x-api-key'] = import.meta.env.VITE_API_KEY;

        // 2. Handle Authentication Token
        const token = localStorage.getItem('token');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }

        // 3. Handle Localization if applicable
        const lang = localStorage.getItem('i18n_locale'); // Adjust based on your i18n setup
        if (lang) {
            config.headers['x-localization'] = lang;
        }

        return config;
    },
    error => Promise.reject(error)
);
