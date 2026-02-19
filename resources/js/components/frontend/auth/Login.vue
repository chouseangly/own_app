<template >
    <LoadingComponent :props="loading"  />
    <div>
        <h1>
            Login page
        </h1>
    </div>
</template>
<script>
import LoadingComponent from '../../admin/components/LoadingComponent.vue';

export default {
    name: 'Login',
    components:{
        LoadingComponent
    },
    data(){
        return {
            loading:{
                isActive: false
            }
        }
    }

}
</script>

