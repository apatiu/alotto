<template>
    <div class="p-field">
        <label for="">ขนาด</label>
        <Dropdown :modelValue="modelValue"
                  @update:modelValue="$emit('update:ModelValue',$event)"
                  :options="items" optionLabel="name"
                  placeholder="เลือกขนาด"
                  filter></Dropdown>
    </div>
</template>

<script>
export default {
    name: "SelectProductSize",
    props: {
        modelValue: {default: null}
    },
    data() {
        return {
            items: [],
        }
    },
    created() {
        axios.get(route('api.product-sizes.index'))
            .then(({data}) => {
                this.items = data
            })
    }
}
</script>

<style scoped>

</style>
