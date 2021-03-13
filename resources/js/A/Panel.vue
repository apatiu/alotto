<template>
    <div class="bg-white rounded-md shadow overflow-x-auto">
        <div class="px-6 py-4">
            <slot></slot>
        </div>
        <div class="px-6 py-4 bg-gray-50 flex justify-end items-center" v-if="$slots.footer">
            <slot name="footer" v-if="$slots.footer"></slot>
        </div>

    </div>
</template>

<script>
    export default {
        name: "Panel"
    }
</script>

<style scoped>

</style>
