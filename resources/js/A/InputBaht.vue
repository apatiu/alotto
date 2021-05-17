<template>
    <div class="p-inputgroup">
        <InputNumber
            :modelValue="modelValue"
            @update:modelValue="$emit('update:modelValue',$event)"
            inputClass="text-right"
            v-bind="$attrs"></InputNumber>
        <span class="p-inputgroup-addon">บาท</span>
    </div>
</template>

<script>
export default {
    name: "InputBaht",
    inheritAttrs: false,
    props: {
        modelValue: [Object,Number]
    }
}
</script>

<style scoped>

</style>
