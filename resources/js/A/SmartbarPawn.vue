<template>
    <div class="p-inputgroup">
        <AutoComplete
            v-model="query"
            placeholder="ค้นหาใบขายฝาก"></AutoComplete>
        <Button label="รับขายฝาก" @click="createPawn"></Button>
    </div>
</template>

<script>
export default {
    name: "SmartbarPawn",
    data() {
        return {
            query: null
        }
    },
    methods: {
        createPawn() {

        }
    }
}
</script>

<style scoped>

</style>
