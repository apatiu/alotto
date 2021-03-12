<template>
    <div class="sidebar bg-red-700 w-20 text-white flex flex-col items-center">
    <div v-for="item in items">
        <img :src="item.icon" alt="">
        <span>{{ item.title }}</span>
    </div>
    </div>
</template>

<script>
export default {
    name: "Sidebar",
    props: ['items']
}
</script>

<style scoped>
.sidebar {
    height: calc(100vh - 65px);
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4px;
    width: 5rem;
    color: white;
    background-color: #991B1B;
}
</style>
