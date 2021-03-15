<template>
    <div class="flex flex-col">
        <label for="">ผู้ค้าส่ง</label>
        <Dropdown :modelValue="modelValue"
                  @update:modelValue="$emit('update:modelValue',$event)"
                  :options="options"
                  optionLabel="name"
                  optionValue="name"
                  :editable="true"/>
        <input-error :message="error"></input-error>
    </div>
</template>

<script>

import InputError from "@/Jetstream/InputError";

export default {
    name: "SelectSupplier",
    components: {InputError},
    props: ['modelValue', 'error'],
    data() {
        return {
            options: this.$page.props.suppliers,
            selected: this.value
        }
    }
}
</script>

<style scoped>

</style>
