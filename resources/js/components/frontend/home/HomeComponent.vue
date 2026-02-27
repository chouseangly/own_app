<template >
   <SliderComponent />

   <CategoryComponent />

   <ProductBrandComponent />
</template>
<script>

import CategoryComponent from './CategoryComponent.vue';
import SliderComponent from './SliderComponent.vue';
import ProductBrandComponent from './ProductBrandComponent.vue';
export default {
    name: 'HomeComponent',
    components: {
        SliderComponent,
        CategoryComponent,
        ProductBrandComponent

    }
}
</script>

