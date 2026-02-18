<template>
  <div v-if="theme === 'loading'">
    <LoadingComponent :props="{isActive: true}" />
  </div>

  <div v-if="theme === 'frontend'">
    <FrontendNavbarComponent />
  </div>
</template>

<script>
import LoadingComponent from './components/admin/components/LoadingComponent.vue'
import FrontendNavbarComponent from './components/layout/frontend/FrontendNavbarComponent.vue';

export default {
    name: 'DefaultComponent',
    components: {
        LoadingComponent,
        FrontendNavbarComponent
    },
    data() {
        return {
            // Set this to 'loading' to see your loader
            theme: 'loading'
        }
    },
    mounted() {
        // Switch to 'frontend' after 3 seconds to test the transition
        setTimeout(() => {
            this.theme = 'frontend';
        }, 3000);
    }
}
</script>
