<template>
    <input type="text" :value="value" @input="$emit('input','aresult')">
</template>

<script>
export default {
    name: "AInput",
    emits: ['input'],
    props: {
        value: String
    }
}
</script>

<style scoped>

</style>
