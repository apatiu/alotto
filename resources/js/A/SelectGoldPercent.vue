<template>
    <span class="p-float-label">
        <Dropdown class="w-full p-filled"
              :options="$page.props.gold_percents"
              optionLabel="name"
              optionValue="id"
              :modelValue="modelValue"
              @update:modelValue="$emit('update:modelValue',$event)"></Dropdown>
        <label for="">เปอร์เซ็นทอง</label>
    </span>
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
