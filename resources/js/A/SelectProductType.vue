<template>
    <label for="" v-if="showLabel">ประเภทสินค้า</label>
    <Dropdown :modelValue="modelValue"
              @update:modelValue="$emit('update:modelValue',$event)"
              :options="types" optionLabel="name"
              placeholder="เลือกประเภท"
              filter></Dropdown>
    <input-error :model-value="errors"></input-error>
</template>

<script>
import InputError from "@/Jetstream/InputError";

export default {
    name: "SelectProductType",
    components: {InputError},
    props: {
        modelValue: {default: null},
        errors: {default: []},
        showLabel: {type: Boolean, default: true},
    },
    data() {
        return {
            types: null,
        }
    },
    created() {
        axios.get(route('api.product-types.index'))
            .then(response => {
                this.types = response.data
            })
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
