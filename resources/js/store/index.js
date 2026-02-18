import { createStore } from "vuex";
import { auth } from "./modules/auth";
import createPersistedState from "vuex-persistedstate"; // Ensure this is installed via npm

export default createStore({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth // This includes the login, register, and verifyOtp actions we created
    },
    plugins: [
        createPersistedState({
            paths: ["auth", "globalState", "frontendCart", "posCart"],
        }),
    ]
});
