<template>
    <label for="">เปอร์เซ็นทอง</label>
    <Dropdown class="w-full p-filled"
              :options="items"
              optionLabel="gold_percent"
              optionValue="id"
              :modelValue="modelValue"
              @update:modelValue="onUpdate"></Dropdown>


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
    },
    methods: {
        onUpdate(e) {
            this.$emit('update:modelValue', e)
        }
    }
}
</script>

<style scoped>

</style>
