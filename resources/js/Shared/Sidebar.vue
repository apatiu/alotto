<template>
    <div v-for="item in items" class="r">
        <a :href="route(item.route)"
           class="text-center flex flex-col w-16 h-16 p-2 rounded border bg-white hover:bg-blue-100">
            <img :src="icon_url(item.icon)" alt="" class="h-8">
            <span class="text-xs">{{ item.title }}</span>
        </a>
    </div>
</template>

<script>
    export default {
        name: "Sidebar",
        props: ['items'],
        methods: {
            icon_url(icon) {
                return '/icon/svg/' + icon + '.svg';
            }
        }
    }
</script>

<style scoped>
</style>
