<template>
    <header
        :class="isSticky === true ? 'fixed top-0 left-0 z-30 w-full mb-5 sm:mb-8 shadow-xs bg-white' : 'mb-5 sm:mb-8 shadow-xs bg-white'">
        <div class="mx-auto max-w-screen-xl py-3.5 px-4 lg:py-2">
            <div class="flex items-center justify-between gap-5">
                <div class="flex items-center flex-shrink-0 gap-5">
                    <button type="button" class="leading-none block lg:hidden"
                        @click.prevent="showTarget('mobile-sidebar-canvas', 'canvas-active')">
                        <i class="lab-line-humburger text-xl"></i>
                    </button>

                    <router-link :to="{ name: 'frontend.home' }"
                        class="router-link-active router-link-exact-active flex-shrink-0">
                        <img class="w-28 sm:w-32" src='images/required/theme-logo.png' alt="logo">
                    </router-link>
                </div>

                <button type="button" class="leading-none block lg:hidden"
                    @click.prevent="showTarget('search', 'search-active')">
                    <i class="lab-line-search text-xl"></i>
                </button>
                <nav class="header-nav hidden lg:block font-bold">
                    <ul class="header-nav-list flex gap-6">
                        <li class="header-nav-item">
                            <router-link class="header-nav-menu"
                                :class="checkIsPathAndRoutePathSame('/home') ? 'router-link-active router-link-exact-active' : ''"
                                :to="{ name: 'frontend.home' }">
                                {{ $t("label.home") }}
                            </router-link>
                        </li>

                        <li class="header-nav-item group/category">
                            <button type="button" class="header-nav-menu down-arrow">
                                {{ $t('label.categories') }}
                            </button>

                            <div
                                class="fixed top-[64px] left-0 z-10 w-full origin-top scale-y-0 transition-all duration-300 group-hover/category:scale-y-100">
                                <div class="container">
                                    <div class="w-full rounded-b-2xl shadow-paper bg-white">
                                        <nav class="w-full flex items-center justify-center border-b border-gray-100">
                                            <router-link v-for="(category, index) in categories" :key="index"
                                                :to="{ name: 'frontend.home', query: { category: category.slug } }"
                                                @mouseover.prevent="activeTab = 'category_' + category.slug"
                                                class="capitalize text-sm font-semibold tracking-wide px-5 py-4 transition-all duration-300 relative before:content-[''] before:absolute before:bottom-0 before:left-0 before:h-0.5 before:bg-primary hover:text-primary"
                                                :class="{ 'text-primary before:w-full': activeTab === 'category_' + category.slug }">
                                                {{ category.name }}
                                            </router-link>
                                        </nav>
                                        <div v-for="category in categories" :key="category.slug">
                                            <div v-if="category.children && category.children.length > 0"
                                                :class="{ 'flex': activeTab === 'category_' + category.slug, 'hidden': activeTab !== 'category_' + category.slug }"
                                                class="items-start gap-5 pb-5">

                                                <div class="w-60 h-80 flex-shrink-0 pt-5 ltr:pl-5 rtl:pr-5">
                                                    <img class="w-full h-full object-top object-cover rounded-lg"
                                                        :src="category.cover" alt="category" />
                                                </div>

                                                <div
                                                    class="w-full h-80 thin-scrolling pt-5 ltr:pr-5 rtl:pl-5 overflow-y-auto">
                                                    <div class="w-full grid gap-5 grid-cols-3">
                                                        <div v-for="child in category.children" :key="child.slug"
                                                            class="self-start">
                                                            <h3
                                                                class="text-sm font-semibold capitalize pb-3 border-b border-slate-200">
                                                                <router-link
                                                                    :to="{ name: 'frontend.home', query: { category: child.slug } }"
                                                                    class="hover:text-primary transition-all duration-300">
                                                                    {{ child.name }}
                                                                </router-link>
                                                            </h3>

                                                            <nav v-if="child.children && child.children.length > 0"
                                                                class="flex flex-col mt-2">
                                                                <MenuChildrenComponent :categories="child.children" />
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
                                :to="{ name: 'frontend.home' }">
                                {{ $t('label.offers') }}
                            </router-link>
                        </li>
                    </ul>
                </nav>
                <form @submit.prevent="search"
                    class="hidden w-full lg:w-80 h-10 rounded-3xl lg:flex items-center gap-2 px-4 border border-gray-100 bg-gray-100 transition-all duration-300 focus-within:border-primary focus-within:bg-white">
                    <button class="lab-line-search text-lg flex-shrink-0"></button>
                    <input v-model="searchProduct" class="w-full h-full" type="search"
                        :placeholder="$t('label.search') + '...'" />
                    <button @click="resetSearch" type="button" v-if="searchProduct"
                        class="text-sm text-red-500 fa-regular fa-circle-xmark"></button>
                </form>

                <div v-if="setting.site_language_switch === enums.activityEnum.ENABLE"
                    class="relative group hidden lg:block">
                    <button type="button" class="flex items-center gap-2 py-5 down-arrow">
                        <img :src="language.image" alt="language" class="w-4 h-4 rounded-full" />
                        <span class="font-semibold capitalize">{{ language.name }}</span>
                    </button>
                    <ul
                        class="w-40 absolute top-16 ltr:right-0 rtl:left-0 shadow-paper rounded-lg z-10 p-2 bg-white transition-all duration-300 origin-top scale-y-0 group-hover:scale-y-100">
                        <li v-for="(LoopLanguage, index) in languages" :key="index"
                            @click.prevent="changeLanguage(LoopLanguage.id, LoopLanguage.code, LoopLanguage.display_mode)"
                            class="flex items-center gap-3 px-2 py-1.5 rounded-lg relative w-full cursor-pointer transition-all duration-300 hover:bg-slate-100">
                            <img :src="LoopLanguage.image" alt="flags" class="w-4 flex-shrink-0" />
                            <span class="text-sm font-medium capitalize flex-auto">{{ LoopLanguage.name }}</span>
                        </li>
                    </ul>
                </div>
                <!-- Wishlist Start -->

                <router-link class="hidden lg:block relative" :to="{ name: 'frontend.home' }">
                    <i class="lab-line-heart text-xl"></i>
                    <span v-if="wishlists.length > 0"
                        class="absolute top-2 ltr:-right-2 rtl:-left-2 text-[10px] font-medium h-4 px-1 !leading-[14px] text-center rounded-full border border-white text-white bg-primary">
                        {{ wishlists.length }}
                    </span>
                </router-link>
                <!-- WishList End -->
                <!-- My Account Start -->
                <div class="relative hidden lg:block group">
                    <button type="button" class="lab-line-user text-xl py-5"></button>
                    <div v-if="logged"
                        class="w-60 absolute top-15 ltr:-right-10 rtl:-left-10  z-10 rounded-2xl overflow-hidden shadow-card bg-white transition-all duration-300 origin-top scale-y-0 group-hover:scale-y-100">
                        <div class="flex items-center gap-3 p-4 border-b border-[#EFF0F6]">
                            <img :src="profile.image" alt="avatar"
                                class="w-11 h-11 rounded-full object-cover flex-shrink-0">
                            <dl class="w-full">
                                <dt class="font-semibold capitalize whitespace-nowrap mb-0.5">
                                    {{ textShortener(profile.name, 20) }}
                                </dt>
                                <dd class="text-sm font-medium whitespace-nowrap text-text" v-if="profile.phone">
                                    <span dir="ltr">{{ profile.country_code }}{{ profile.phone }}</span>
                                </dd>
                            </dl>
                        </div>
                        <nav class="flex flex-col py-2">
                            <router-link
                                v-if="profile.role_id !== enums.roleEnum.CUSTOMER && Object.keys(authDefaultPermission).length > 0"
                                class="flex items-center gap-3 px-4 py-2 transition-all duration-500 hover:bg-gray-100"
                                :to="{ name:'frontend.home' }">
                                <i class="text-sm text-[#A0A3BD]" :class="defaultMenu?.icon"></i>
                                <span class="text-sm font-medium capitalize whitespace-nowrap">
                                    {{ $t('menu.' + defaultMenu?.language) }}
                                </span>
                            </router-link>

                            <router-link
                                class="flex items-center gap-3 px-4 py-2 transition-all duration-500 hover:bg-gray-100"
                                :to="{ name: 'frontend.account.orderHistory' }">
                                <i class="text-sm text-[#A0A3BD] lab-fill-bag"></i>
                                <span class="text-sm font-medium capitalize whitespace-nowrap">
                                    {{ $t('menu.order_history') }}
                                </span>
                            </router-link>

                            <router-link
                                class="flex items-center gap-3 px-4 py-2 transition-all duration-500 hover:bg-gray-100"
                                :to="{ name: 'frontend.account.returnOrders' }">
                                <i class="text-sm text-[#A0A3BD] lab-fill-refresh"></i>
                                <span class="text-sm font-medium capitalize whitespace-nowrap">
                                    {{ $t('menu.return_orders') }}
                                </span>
                            </router-link>

                            <router-link
                                class="flex items-center gap-3 px-4 py-2 transition-all duration-500 hover:bg-gray-100"
                                :to="{ name: 'frontend.account.accountInfo' }">
                                <i class="text-sm text-[#A0A3BD] lab-fill-user"></i>
                                <span class="text-sm font-medium capitalize whitespace-nowrap">
                                    {{ $t('menu.account_info') }}
                                </span>
                            </router-link>

                            <router-link
                                class="flex items-center gap-3 px-4 py-2 transition-all duration-500 hover:bg-gray-100"
                                :to="{ name: 'frontend.account.changePassword' }">
                                <i class="text-sm text-[#A0A3BD] lab-fill-key"></i>
                                <span class="text-sm font-medium capitalize whitespace-nowrap">
                                    {{ $t('menu.change_password') }}
                                </span>
                            </router-link>

                            <router-link
                                class="flex items-center gap-3 px-4 py-2 transition-all duration-500 hover:bg-gray-100"
                                :to="{ name: 'frontend.account.address' }">
                                <i class="text-sm text-[#A0A3BD] lab-fill-location"></i>
                                <span class="text-sm font-medium capitalize whitespace-nowrap">
                                    {{ $t('menu.address') }}
                                </span>
                            </router-link>

                            <button @click.prevent="logout()"
                                class="flex items-center gap-3 px-4 py-2 transition-all duration-500 hover:bg-gray-100">
                                <i class="text-sm text-[#A0A3BD] lab-fill-logout"></i>
                                <span class="text-sm font-medium capitalize whitespace-nowrap">
                                    {{ $t('button.logout') }}
                                </span>
                            </button>
                        </nav>
                    </div>

                    <div v-else
                        class="w-64 absolute top-15 ltr:-right-10 rtl:-left-10 z-10 p-4 rounded-2xl overflow-hidden shadow-card bg-white transition-all duration-300 origin-top scale-y-0 group-hover:scale-y-100">
                        <router-link
                            class="!text-primary !bg-[#FFF4F1] w-full text-center h-12 leading-12 font-semibold tracking-wide rounded-full whitespace-nowrap"
                            :to="{ name: 'frontend.register' }">
                            {{ $t('button.register_your_account') }}
                        </router-link>
                        <span class="block font-medium uppercase text-center py-3">{{ $t('label.or') }}</span>
                        <router-link
                            class="w-full text-center h-12 leading-12 font-semibold tracking-wide rounded-full whitespace-nowrap text-white bg-primary"
                            :to="{ name: 'frontend.login' }">
                            {{ $t('button.login_to_your_account') }}
                        </router-link>
                    </div>
                </div>
                <!-- My Account End -->


                <!-- Card Button Start -->
                <button @click.prevent="openCanvas('cart-canvas')" type="button"
                    class="hidden lg:block flex-shrink-0 relative">
                    <i
                        class="lab-line-bag text-xl w-10 h-10 !leading-10 text-center rounded-full bg-secondary text-white"></i>
                    <span v-if="carts.length > 0"
                        class="absolute top-4 ltr:right-1 rtl:left-1 text-[10px] font-medium h-4 px-1 leading-[14px] text-center rounded-full border border-heading text-white bg-primary">
                        {{ carts.length }}
                    </span>
                </button>
                <!-- Card Button End -->
            </div>
        </div>
    </header>

    <!-- Mobile Search Start -->
    <form @submit.prevent="search" id="search"
        class="w-full  lg:w-auto fixed inset-0 z-30 py-5 px-4 bg-white transition-all duration-500 origin-top scale-y-0">
        <div class="flex items-center justify-between mb-4">
            <router-link :to="{ name: 'frontend.home' }"
                class="router-link-active router-link-exact-active flex-shrink-0">
                <img class="w-28 sm:w-32" :src="setting.theme_logo" alt="logo">
            </router-link>
            <button type="button">
                <i @click.prevent="hideTarget('search', 'search-active')"
                    class="lab-line-circle-cross text-xl text-danger"></i>
            </button>
        </div>
        <div
            class="w-full h-10 rounded-3xl flex items-center gap-2 px-4 mb-4 border border-gray-100 bg-gray-100 transition-all duration-300 focus-within:border-primary focus-within:bg-white">
            <button class="lab-line-search text-lg flex-shrink-0"></button>
            <input id="searchSomething" v-model="searchProduct" @keyup="searchElement" class="w-full h-full"
                type="search" :placeholder="$t('label.search') + '...'">
        </div>
        <div class="lg:hidden h-[calc(100vh_-_140px)] rounded-xl overflow-y-auto p-4 bg-gray-100">
            <ul v-if="searchProductLists.length > 0" id="searchProductLists">
                <li :key="searchProductList.name"
                    class="py-1 hover:px-2 whitespace-nowrap overflow-hidden text-ellipsis rounded-lg transition-all duration-300 hover:bg-white hover:text-primary"
                    @click.prevent="goSearchProduct(searchProductList.slug)"
                    v-for="searchProductList in searchProductLists">{{ searchProductList.name }}</li>
            </ul>
        </div>
    </form>
    <!-- Mobile Search End -->

    <!-- Notification Start -->
    <div id="order-modal" v-if="orderNotificationStatus" ref="orderNotificationModal" class="modal active ff-modal">
        <div class="modal-dialog max-w-[360px] p-6 text-center relative">
            <button @click.prevent="closeOrderNotificationModal('order-modal', 'modal-active')"
                class="modal-close absolute top-4 right-4">
                <i class="fa-regular fa-circle-xmark"></i>
            </button>
            <h3 class="text-[18px] font-semibold leading-8 mb-6">
                {{ orderNotificationMessage }}
                <span class="block">{{ $t('message.please_check_your_order_list') }}</span>
            </h3>
            <router-link :to="{ name:'frontend.home' }"
                class="db-btn h-[38px] shadow-[0px_6px_10px_rgba(255,_0,_107,_0.24)] bg-primary text-white">
                {{ $t('button.let_me_check') }}
            </router-link>
        </div>
    </div>
    <!-- Notification End -->
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
        logged() { return this.$store.getters['auth/authStatus']; },
        profile() { return this.$store.getters['auth/authInfo'] || {}; },
        setting() { return this.$store.getters['frontendSetting/lists'] || {}; },
        categories() { return this.$store.getters['frontendProductCategory/trees'] || []; },
        language() { return this.$store.getters['frontendLanguage/show'] || {}; },
        languages() { return this.$store.getters['frontendLanguage/lists'] || []; },
        wishlists() { return this.$store.getters['frontendWishlist/lists'] || []; },
        carts() { return this.$store.getters['frontendCart/lists'] || []; },

        // Ensure these match your actual store structure
        authDefaultPermission() { return this.$store.getters['auth/authDefaultPermission'] || {}; },
        defaultMenu() { return this.$store.getters['auth/authDefaultMenu'] || {}; }
    },
    mounted() {
        this.currentRoute = this.$route.path;

        // 1. Restore User Session if token exists
        if (this.logged && !this.profile.name) {
            this.$store.dispatch('auth/updateUser');
        }

        // 2. Fetch other data
        this.$store.dispatch('frontendProductCategory/trees');
        this.$store.dispatch('frontendWishlist/lists');
    },
    // FIXED: Watcher to set initial active tab once data is loaded
    watch: {
        categories: {
            handler(newVal) {
                if (newVal && newVal.length > 0 && !this.activeTab) {
                    this.activeTab = 'category_' + newVal[0].slug;
                }
            },
            immediate: true
        }
    },
    methods: {
        showTarget(id, cClass) { targetService.showTarget(id, cClass); },
        hideTarget(id, cClass) { targetService.hideTarget(id, cClass); },
        textShortener(text, number = 30) { return appService.textShortener(text, number); },
        checkIsPathAndRoutePathSame(path) { return this.currentRoute === path; },
        resetSearch() { this.searchProduct = ""; },
        logout() {
           this.$store.dispatch("auth/logout").then(() => {
            this.$store.dispatch("frontendWishlist/reset");
            this.$router.push({ name: "frontend.home" });
        });
        }
    }
};
</script>
