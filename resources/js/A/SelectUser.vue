<template>
    <Dropdown :modelValue="modelValue"
              @update:modelValue="$emit('update:modelValue',$event)"
              :options="options"
              optionValue="id"
              optionLabel="name"
              placeholder="เลือกพนักงาน"></Dropdown>
</template>

<script>
export default {
    name: "SelectUser",
    props: ['modelValue'],
    data() {
        return {
            options: [],
        }
    },
    created() {
        axios.get('/api/users')
            .then(res => {
                this.options = res.data
            })
    }
}
</script>

<style scoped>

</style>
