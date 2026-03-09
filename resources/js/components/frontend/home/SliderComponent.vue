<template>
    <LoadingComponent :props="loading" />

    <section class="mb-10 sm:mb-20">
        <div class="mx-auto max-w-screen-xl relative group">

            <!-- Custom Navigation Buttons -->
            <button class="custom-prev absolute left-4 top-1/2 -translate-y-1/2 z-10
                       bg-white shadow-lg w-10 h-10 rounded-full opacity-0 group-hover:opacity-100
                       flex items-center justify-center hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button class="custom-next absolute right-4 top-1/2 -translate-y-1/2 z-10
                       bg-white shadow-lg w-10 h-10 rounded-full opacity-0 group-hover:opacity-100
                       flex items-center justify-center hover:bg-gray-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <Swiper v-if="sliders.length > 0" dir="rtl" :modules="modules" :slides-per-view="1" :speed="1000"
                :loop="true" :navigation="{
                    prevEl: '.custom-prev',
                    nextEl: '.custom-next'
                }" :pagination="{ clickable: true }" :autoplay="{ delay: 2500 }" class="banner-swiper">
                <SwiperSlide v-for="slider in sliders" :key="slider.id">
                    <div v-if="slider.link">
                        <a :href="slider.link">
                            <img class="w-full rounded-2xl" :src="slider.image" alt="banner">
                        </a>
                    </div>
                    <div v-else>
                        <img class="w-full rounded-2xl" :src="slider.image" alt="banner">
                    </div>
                </SwiperSlide>
            </Swiper>

        </div>
    </section>
</template>

<script>
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import LoadingComponent from '../../admin/components/LoadingComponent.vue';
import statusEnum from '../../../../enums/modules/statusEnum';

export default {
    name: "SliderComponent",
    components: {
        Swiper,
        SwiperSlide,
        LoadingComponent
    },
    setup() {
        return {
            modules: [Navigation, Pagination, Autoplay],
        }
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            sliderProps: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_type: 'desc',
                    status: statusEnum.ACTIVE
                }
            }
        }
    },
    computed: {
        sliders: function () {
            return this.$store.getters['frontendSlider/lists'] || [];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendSlider/lists", this.sliderProps.search).then((res) => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    }
}
</script>
