import { createStore } from "vuex";
import { auth } from "./modules/auth";
import createPersistedState from "vuex-persistedstate"; // Ensure this is installed via npm
import { frontendProductCategory } from "./modules/frontend/productCategory";
import { slider } from "./modules/slider";
import { frontendSlider } from "./modules/frontend/frontendSlider";
import { frontendProductBrand } from "./modules/frontend/frontendProductBrand";
import { barcode } from "./modules/barcode";
import { frontendEditProfile } from "./modules/frontend/frontendEditProfile";
import axios from "axios";

// ADD THIS BLOCK — restores auth header on every page refresh
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export default createStore({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth ,
        frontendProductCategory,
        slider,
        frontendSlider,
        frontendProductBrand,
        barcode,
        frontendEditProfile
    },

    plugins: [
        createPersistedState({
            paths: ["auth", "globalState", "frontendCart", "posCart"],
        }),
    ]
});
