<template>
    <label for="">เปอร์เซ็นทอง</label>
    <Dropdown class="w-full"
        :options="$page.props.gold_percents"
        optionLabel="name"
        optionValue="id"
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue',$event)"
    ></Dropdown>
</template>

<script>
export default {
    name: "SelectGoldPercent",
    props: ['modelValue'],
    data() {
        return {}
    }
}
</script>

<style scoped>

</style>
