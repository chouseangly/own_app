<template>
  <div v-if="theme === 'loading'">
    <LoadingComponent :props="{isActive: true}" />
  </div>

  <div v-else-if="theme === 'frontend'">
    <FrontendNavbarComponent />
    <router-view></router-view>
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
            theme: 'loading'
        }
    },
    mounted() {
        // Simulate loading then show the app
        setTimeout(() => {
            this.theme = 'frontend';
        }, 1000);
    }
}
</script>
