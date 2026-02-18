<template >
    <LoadingComponent :props="loading"/>
    <div>
        <!-- <img src="images\required\auth.jpg" alt=""> -->
         <h1>register</h1>
    </div>
</template>
<script>
import LoadingComponent from '../../admin/components/LoadingComponent.vue';

export default {
    name: 'Register',
    components: {
        LoadingComponent
    }
}
</script>
