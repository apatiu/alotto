<template>
    <Dropdown :modelValue="modelValue"
              @update:modelValue="$emit('update:modelValue',$event)"
              :options="options"
              optionValue="code"
              optionLabel="name"
              placeholder="Select a bank"></Dropdown>
</template>

<script>
export default {
    name: "SelectBank",
    props: ['modelValue'],
    data() {
        return {
            options: [],
        }
    },
    created() {
        axios.get('/api/banks')
            .then(res => {
                this.options = res.data
            })
    }
}
</script>

<style scoped>

</style>
