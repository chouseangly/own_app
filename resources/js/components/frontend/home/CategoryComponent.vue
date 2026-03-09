<template>
  <LoadingComponent :props="loading" />

  <section v-if="categories?.length" class="sm:mb-10">
    <div class="mx-auto max-w-screen-xl ">
      <!-- Title -->
      <div class="flex justify-between">
            <h2 class="text-2xl sm:text-4xl font-bold mb-6">
        {{ $t('label.browse_by_categories') }}
      </h2>

      <!-- Navigation Buttons -->
      <div class="flex gap-4">
        <button
        class="cate-prev -translate-y-1/2
               w-10 h-10 bg-red-200 hover:bg-red-400 rounded-full flex items-center justify-center
               shadow-sm transition "
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-white" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <button
        class="cate-next -translate-y-1/2
               w-10 h-10 bg-red-200 hover:bg-red-400 rounded-full flex items-center justify-center
               shadow-sm transition "
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-white" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
      </button>

      </div>
      </div>
      <!-- Swiper -->
      <Swiper
        v-if="categories.length > 0"
        :key="categories.length"
        :modules="modules"
        :loop="categories.length > 6"
        :navigation="{ prevEl: '.cate-prev', nextEl: '.cate-next' }"
        :autoplay="{ delay: 3000 }"
        :speed="800"
        :breakpoints="breakpoints"
        class="category-swiper"
      >

        <SwiperSlide
          v-for="category in categories"
          :key="category.id"
          class="flex flex-col items-center text-center w-28 sm:w-32 md:w-36"
        >
          <router-link
            :to="{ name: 'frontend.product', query: { category: category.slug } }"
            class="flex flex-col items-center group"
          >
            <img
              :src="category.thumb"
              alt="category"
              class="w-full h-28 sm:h-32 md:h-36 object-cover rounded-xl mb-2"
            />
            <span class="text-sm sm:text-base truncate max-w-full group-hover:text-red-400">
              {{ category.name }}
            </span>
          </router-link>
        </SwiperSlide>
      </Swiper>
    </div>
  </section>
</template>

<script>
import { Autoplay, Pagination, Navigation } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import LoadingComponent from '../../admin/components/LoadingComponent.vue';
import statusEnum from '../../../../enums/modules/statusEnum';

export default {
  name: 'CategoryComponent',
  components: { LoadingComponent, Swiper, SwiperSlide },
  setup() {
    return {
      modules: [Navigation, Pagination, Autoplay],
    };
  },
  data() {
    return {
      loading: { isActive: false },
      breakpoints: {
        0: { slidesPerView: 'auto', spaceBetween: 16 },
        640: { slidesPerView: 4, spaceBetween: 24 },
        768: { slidesPerView: 5, spaceBetween: 24 },
        1024: { slidesPerView: 6, spaceBetween: 24 },
      },
    };
  },
  computed: {
    categories() {
      return this.$store.getters['frontendProductCategory/lists'];
    },
  },
  mounted() {
    this.loading.isActive = true;
    this.$store
      .dispatch('frontendProductCategory/lists', {
        paginate: 0,
        order_column: 'id',
        order_type: 'desc',
        status: statusEnum.ACTIVE,
      })
      .finally(() => {
        this.loading.isActive = false;
      });
  },
};
</script>
