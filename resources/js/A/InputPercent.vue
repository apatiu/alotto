<template>
    <div class="p-inputgroup">
        <InputNumber
            :modelValue="modelValue"
            @update:modelValue="$emit('update:modelValue',$event)"
            inputClass="text-right"
            minFractionDigits="2"
            v-bind="$attrs"></InputNumber>
        <span class="p-inputgroup-addon">%</span>
    </div>
</template>

<script>
export default {
    name: "InputPercent",
    inheritAttrs: false,
    props: {
        modelValue: [Object,Number]
    }
}
</script>

<style scoped>

</style>
