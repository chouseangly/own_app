<template >
   <SliderComponent />

   <CategoryComponent />

   <ProductBrandComponent />
</template>
<script>

import CategoryComponent from './CategoryComponent.vue';
import ProductBrandComponent from './ProductBrandComponent.vue';
import SliderComponent from './SliderComponent.vue';
export default {
    name: 'HomeComponent',
    components: {
        SliderComponent,
        CategoryComponent,
        ProductBrandComponent

    }
}
</script>

