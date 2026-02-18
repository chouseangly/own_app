<template>
  <div v-if="theme === 'loading'">
   <LoadingComponent :props="{isActive:true}" />
  </div>

  <div v-if="theme === 'frontend'">
        <FrontendNavbarComponent />
  </div>
</template>

<script>
import LoadingComponent from './components/admin/components/LoadingComponent.vue'
import FrontendNavbarComponent from './components/layout/frontend/FrontendNavbarComponent.vue';
    export default{
        name: 'DefaultComponent',
        components: {
            LoadingComponent,
            FrontendNavbarComponent
        }
    }
</script>
