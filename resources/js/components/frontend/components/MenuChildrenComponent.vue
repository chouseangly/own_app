<template>
    <div class="p-0 m-0">
        <div v-for="category in categories" :key="category.slug" class="mb-1">
            <router-link
                :to="{ name: 'frontend.home', query: { category: category.slug } }"
                class="text-sm capitalize py-1 transition-all duration-300 hover:text-primary hover:underline flex items-center gap-1"
                :class="!icon ? 'font-medium text-slate-700' : 'text-slate-500'">
                <i v-if="icon" class="lab lab-line-chevron-right text-[10px]"></i>
                {{ category.name }}
            </router-link>

            <MenuChildrenComponent
                v-if="category.children && category.children.length > 0"
                :icon="true"
                class="ltr:ml-3 rtl:mr-3 border-l border-gray-100"
                :categories="category.children"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "MenuChildrenComponent",
    props: {
        categories: {
            type: Array,
            required: true
        },
        icon: {
            type: Boolean,
            default: false
        },
    },
}
</script>
