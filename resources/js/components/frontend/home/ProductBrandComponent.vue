<template>
  <LoadingComponent :props="loading" />

  <section class="mb-6 sm:mb-12" v-if="brands.length > 0">
    <div class="mx-auto max-w-screen-xl">
      <div class="flex justify-between items-center mb-6">
        <h2 class="capitalize text-2xl sm:text-4xl font-bold">
          {{ $t('label.popular_brands') }}
        </h2>

        <div class="flex gap-4">
          <button
            class="brand-prev z-10 w-10 h-10 bg-red-200 hover:bg-red-400 rounded-full flex items-center justify-center shadow-sm transition"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
          </button>

          <button
            class="brand-next z-10 w-10 h-10 bg-red-200 hover:bg-red-400 rounded-full flex items-center justify-center shadow-sm transition"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
      </div>

      <Swiper
        v-if="brands.length > 0"
        :key="brands.length"
        dir="ltr"
        :modules="modules"
        :loop="brands.length > 6"
        :speed="800"
        :navigation="{ prevEl: '.brand-prev', nextEl: '.brand-next' }"
        :breakpoints="breakpoints"
        class="py-4"
      >
        <SwiperSlide
          v-for="brand in brands"
          :key="brand.id"
          class="flex flex-col items-center text-center w-28 sm:w-32 md:w-36"
        >
          <router-link :to="{ name: 'frontend.product', query: { brand: brand.id } }" class="flex flex-col items-center group">
            <div class="w-full h-28 sm:h-32 md:h-36 bg-white rounded-xl shadow-sm flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
              <img :src="brand.thumb" alt="brand" class="max-h-14 object-contain" />
            </div>
            <span class="text-sm sm:text-base mt-2 truncate max-w-full group-hover:text-red-400 transition-colors">
              {{ brand.name }}
            </span>
          </router-link>
        </SwiperSlide>
      </Swiper>
    </div>
  </section>
</template>
<script>
import statusEnum from "../../../../enums/modules/statusEnum";
import LoadingComponent from "../../admin/components/LoadingComponent.vue";
import {Swiper, SwiperSlide} from "swiper/vue";
import {Autoplay, Navigation, Pagination} from "swiper/modules";
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

export default {
    name: "ProductBrandComponent",
    components: {
        Swiper, SwiperSlide,
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            breakpoints: {
                0: {slidesPerView: 'auto', spaceBetween: 16},
                640: {slidesPerView: 4, spaceBetween: 24},
                768: {slidesPerView: 5, spaceBetween: 24},
                1024: {slidesPerView: 6, spaceBetween: 24}
            },
        }
    },
    setup() {
        return {
            modules: [Navigation, Pagination, Autoplay],
        }
    },
    computed: {
        brands: function () {
            return this.$store.getters["frontendProductBrand/lists"] || [];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendProductBrand/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            status: statusEnum.ACTIVE,
        }).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    }
}
</script>

