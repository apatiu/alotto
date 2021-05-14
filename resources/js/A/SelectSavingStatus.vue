<template>
    <Dropdown
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue',$event)"
        :options="options"
        optionValue="code"
        optionLabel="label"
        placeholder="สถานะ"></Dropdown>
</template>

<script>
export default {
    name: "SelectPawnStatus",
    props: ['modelValue'],
    data() {
        return {
            options: [
                {code: 'open', label: 'เปิดออม'},
                {code: 'close', label: 'ปิดออม'},
                {code: 'cancel', label: 'ยกเลิก'},
            ]
        }
    }
}
</script>

<style scoped>

</style>
