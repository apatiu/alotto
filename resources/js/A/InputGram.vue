<template>
    <div class="p-inputgroup">
        <InputNumber
            :modelValue="modelValue"
            @update:modelValue="$emit('update:modelValue',$event)"
            inputClass="text-right"
            minFractionDigits="2"
            v-bind="$attrs"></InputNumber>
        <span class="p-inputgroup-addon">กรัม</span>
    </div>
</template>

<script>
export default {
    name: "InputGram",
    inheritAttrs: false,
    props: {
        modelValue: [Object,Number]
    }
}
</script>

<style scoped>

</style>
