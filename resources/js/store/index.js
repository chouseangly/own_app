import { createStore } from "vuex";
import { auth } from "./modules/auth";
import createPersistedState from "vuex-persistedstate"; // Ensure this is installed via npm
import { frontendProductCategory } from "./modules/frontend/productCategory";
import { slider } from "./modules/slider";
import { frontendSlider } from "./modules/frontend/frontendSlider";

export default createStore({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth ,
        frontendProductCategory,
        slider,
        frontendSlider
    },

    plugins: [
        createPersistedState({
            paths: ["auth", "globalState", "frontendCart", "posCart"],
        }),
    ]
});
