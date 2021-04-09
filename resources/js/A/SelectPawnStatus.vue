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
                {code: 'new,int', label: 'ใหม่และต่อดอก'},
                {code: 'new', label: 'รายการใหม่'},
                {code: 'int', label: 'ต่อดอก'},
                {code: 'red', label: 'ไถ่ถอน'},
                {code: 'chg', label: 'เปลี่ยนใบ'},
                {code: 'cut', label: 'คัดออก'},
            ]
        }
    }
}
</script>

<style scoped>

</style>
