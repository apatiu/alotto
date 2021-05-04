<template>
    <label for="">เปอร์เซ็นทอง</label>
    <Dropdown class="w-full p-filled"
              :options="items"
              optionLabel="gold_percent"
              optionValue="id"
              :modelValue="modelValue"
              @update:modelValue="$emit('update:modelValue',$event)"></Dropdown>


</template>

<script>
export default {
    name: "SelectGoldPercent",
    props: ['modelValue'],
    data() {
        return {
            items: []
        }
    },
    mounted() {
        axios.get('/api-gold_percents')
            .then(e => {
                this.items = e.data
            });
    }
}
</script>

<style scoped>

</style>
