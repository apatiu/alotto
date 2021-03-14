<template>
    <div class="flex flex-col">
        <label for="">ผู้ค้าส่ง</label>
        <Dropdown v-model="selected" :options="items" optionLabel="name" optionValue="name"/>
        <input-error :message="error"></input-error>
    </div>
</template>

<script>

import InputError from "@/Jetstream/InputError";

export default {
    name: "SelectSupplier",
    components: {InputError},
    props: ['value', 'options', 'error'],
    data() {
        return {
            items: this.options ?? this.$page.props.suppliers,
            selected: this.value
        }
    }
}
</script>

<style scoped>

</style>
