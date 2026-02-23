<template>
    <header
        :class="isSticky === true ? 'fixed top-0 left-0 z-30 w-full mb-5 sm:mb-8 shadow-xs bg-white' : 'mb-5 sm:mb-8 shadow-xs bg-white'">
        <div class="container py-3.5 px-4 lg:py-0">
            <div class="flex items-center justify-between gap-5">
                <div class="flex items-center flex-shrink-0 gap-5">
                    <button type="button" class="leading-none block lg:hidden"
                        @click.prevent="showTarget('mobile-sidebar-canvas', 'canvas-active')">
                        <i class="lab-line-humburger text-xl"></i>
                    </button>

                    <router-link :to="{ name: 'frontend.home' }"
                        class="router-link-active router-link-exact-active flex-shrink-0">
                        <img v-if="setting && setting.theme_logo" class="w-28 sm:w-32" :src="setting.theme_logo" alt="logo">
                        <img v-else class="w-28 sm:w-32" src="images/required/theme-logo.png" alt="logo">
                    </router-link>
                </div>

                <button type="button" class="leading-none block lg:hidden"
                    @click.prevent="showTarget('search', 'search-active')">
                    <i class="lab-line-search text-xl"></i>
                </button>

                <nav class="header-nav hidden lg:block">
                    <ul class="header-nav-list">
                        <li class="header-nav-item">
                            <router-link class="header-nav-menu"
                                :class="checkIsPathAndRoutePathSame('/home') ? 'router-link-active router-link-exact-active' : ''"
                                :to="{ name: 'frontend.home' }">
                                {{ $t("label.home") }}
                            </router-link>
                        </li>

                        <li class="header-nav-item">
                            <button type="button" class="header-nav-menu down-arrow">
                                {{ $t('label.categories') }}
                            </button>
                            <div class="fixed top-[64px] left-0 z-10 w-full origin-top scale-y-0 transition-all duration-300">
                                <div class="container">
                                    <div class="w-full rounded-b-2xl shadow-paper bg-white">
                                        <nav class="w-full flex items-center justify-center">
                                            <router-link v-for="(category, index) in categories" :key="index"
                                                :to="{ name: 'frontend.product', query: { category: category.slug } }"
                                                @mouseover.prevent="activeTab = 'category_' + category.slug"
                                                class="capitalize text-sm font-semibold tracking-wide px-5 py-4 transition-all duration-300 relative before:content-[''] before:absolute before:bottom-0 before:left-0 before:h-0.5 before:bg-primary hover:text-primary"
                                                :class="{ 'text-primary before:w-full before:transition-all before:duration-300': activeTab === 'category_' + category.slug }">
                                                {{ category.name }}
                                            </router-link>
                                        </nav>
                                        <div v-for="category in categories" :key="category.id">
                                            <div v-if="category.children && category.children.length > 0"
                                                :class="{ 'block': activeTab === 'category_' + category.slug, 'hidden': activeTab !== 'category_' + category.slug }"
                                                class="flex items-start gap-5 pb-5 border-t border-gray-200">
                                                <div class="w-60 h-80 flex-shrink-0 pt-5 ltr:pl-5 rtl:pr-5">
                                                    <img class="w-full h-full object-top object-cover rounded-lg"
                                                        :src="category.cover" alt="category" />
                                                </div>
                                                <div class="w-full h-80 thin-scrolling pt-5 ltr:pr-5 rtl:pl-5">
                                                    <div class="w-full grid gap-5 grid-cols-3">
                                                        <div v-for="children in category.children" :key="children.id" class="self-start">
                                                            <h3 class="text-sm font-semibold capitalize pb-3 border-b border-slate-200">
                                                                <router-link
                                                                    :to="{ name: 'frontend.product', query: { category: children.slug } }"
                                                                    class="hover:text-primary transition-all duration-300">
                                                                    {{ children.name }}
                                                                </router-link>
                                                            </h3>
                                                            <nav v-if="children.children && children.children.length > 0" class="flex flex-col mt-2">
                                                                <MenuChildrenComponent :categories="children.children" />
                                                            </nav>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="header-nav-item">
                            <router-link class="header-nav-menu"
                                :class="checkIsPathAndRoutePathSame('/offers') ? 'router-link-active router-link-exact-active' : ''"
                                :to="{ name: 'frontend.offers' }">
                                {{ $t("label.offers") }}
                            </router-link>
                        </li>
                    </ul>
                </nav>

                <form @submit.prevent="search" class="hidden w-full lg:w-80 h-10 rounded-3xl lg:flex items-center gap-2 px-4 border border-gray-100 bg-gray-100 transition-all duration-300 focus-within:border-primary focus-within:bg-white">
                    <button class="lab-line-search text-lg flex-shrink-0"></button>
                    <input v-model="searchProduct" class="w-full h-full" type="search" :placeholder="$t('label.search') + '...'" />
                    <button @click="resetSearch" type="button" v-if="searchProduct" class="text-sm text-red-500 fa-regular fa-circle-xmark"></button>
                </form>

                <div v-if="setting && setting.site_language_switch === enums.activityEnum.ENABLE" class="relative group hidden lg:block">
                    <button type="button" class="flex items-center gap-2 py-5 down-arrow">
                        <img v-if="language" :src="language.image" alt="language" class="w-4 h-4 rounded-full" />
                        <span v-if="language" class="font-semibold capitalize">{{ language.name }}</span>
                    </button>
                    <ul class="w-40 absolute top-16 ltr:right-0 rtl:left-0 shadow-paper rounded-lg z-10 p-2 bg-white transition-all duration-300 origin-top scale-y-0 group-hover:scale-y-100">
                        <li v-for="(LoopLanguage, index) in languages" :key="index"
                            @click.prevent="changeLanguage(LoopLanguage.id, LoopLanguage.code, LoopLanguage.display_mode)"
                            class="flex items-center gap-3 px-2 py-1.5 rounded-lg relative w-full cursor-pointer transition-all duration-300 hover:bg-slate-100">
                            <img :src="LoopLanguage.image" alt="flags" class="w-4 flex-shrink-0" />
                            <span class="text-sm font-medium capitalize flex-auto">{{ LoopLanguage.name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import { onMounted, ref } from 'vue';
import { useCanvas } from '../../../../composables/canvas';
import MenuChildrenComponent from '../../frontend/components/MenuChildrenComponent.vue';
import activityEnum from '../../../../enums/modules/activityEnum';
import roleEnum from '../../../../enums/modules/roleEnum';
import statusEnum from '../../../../enums/modules/statusEnum';
import appService from '../../../../services/appService';
import targetService from '../../../../services/targetService';

export default {
    name: 'FrontendNavbarComponent',
    components: { MenuChildrenComponent },
    setup() {
        const isSticky = ref(false);
        const { openCanvas } = useCanvas();
        onMounted(() => {
            window.addEventListener('scroll', () => {
                isSticky.value = window.scrollY > 0;
            });
        });
        return { isSticky, openCanvas };
    },
    data() {
        return {
            searchProduct: "",
            activeTab: null,
            currentRoute: "",
            searchProductLists: [],
            orderNotificationStatus: false,
            orderNotificationMessage: "",
            enums: { activityEnum, roleEnum },
            languageProps: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE
            }
        };
    },
    computed: {
        logged() { return this.$store.getters.authStatus; },
        setting() { return this.$store.getters['frontendSetting/lists'] || {}; },
        categories() { return this.$store.getters['frontendProductCategory/trees'] || []; },
        language() { return this.$store.getters['frontendLanguage/show'] || {}; },
        languages() { return this.$store.getters['frontendLanguage/lists'] || []; },
        wishlists() { return this.$store.getters['frontendWishlist/lists'] || []; },
        carts() { return this.$store.getters['frontendCart/lists'] || []; },
        profile() { return this.$store.getters.authInfo || {}; },
        authDefaultPermission() { return this.$store.getters.authDefaultPermission || {}; },
        defaultMenu() { return this.$store.getters.authDefaultMenu || {}; }
    },
    mounted() {
        this.currentRoute = this.$route.path;

        // Fetch Categories
        this.$store.dispatch('frontendProductCategory/trees').catch(err => console.error("Vuex Action Error:", err));

        // Fetch Settings & Initialize Language (Uncommented and fixed)
        this.$store.dispatch('frontendSetting/lists').then(res => {
            const defaultLangId = res.data.data.site_default_language;
            this.$store.dispatch('frontendLanguage/show', defaultLangId).then(langRes => {
                if (this.$i18n) this.$i18n.locale = langRes.data.data.code;
            });
            this.$store.dispatch('frontendLanguage/lists', this.languageProps);
        }).catch(err => console.warn("Settings loading failed, using defaults."));
    },
    methods: {
        showTarget(id, cClass) { targetService.showTarget(id, cClass); },
        hideTarget(id, cClass) { targetService.hideTarget(id, cClass); },
        textShortener(text, number = 30) { return appService.textShortener(text, number); },
        checkIsPathAndRoutePathSame(path) { return this.currentRoute === path; },
        resetSearch() { this.searchProduct = ""; },
        logout() {
            this.$store.dispatch("logout").then(() => {
                this.$store.dispatch("frontendWishlist/reset");
                this.$router.push({ name: "frontend.home" });
            });
        }
    }
};
</script>
