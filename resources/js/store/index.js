import { createStore } from "vuex";
import { auth } from "./modules/auth";
import createPersistedState from "vuex-persistedstate"; // Ensure this is installed via npm
import { frontendProductCategory } from "./modules/frontend/productCategory";

export default createStore({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth ,
        frontendProductCategory,
    },

    plugins: [
        createPersistedState({
            paths: ["auth", "globalState", "frontendCart", "posCart"],
        }),
    ]
});
