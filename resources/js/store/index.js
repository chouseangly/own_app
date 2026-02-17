import { createStore } from "vuex";
import { auth } from "./modules/auth";

export default new createStore([
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth
    },
    plugins: [
        createPersistedState({
            paths: ["auth", "globalState", "frontendCart", "posCart"],
        }),
    ]
])
