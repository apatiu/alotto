<template>
    <div class="p-inputgroup">
        <InputNumber
            :modelValue="modelValue"
            @update:modelValue="$emit('update:modelValue',$event)"
            inputClass="text-right"
            minFractionDigits="2"
            ></InputNumber>
        <span class="p-inputgroup-addon">%</span>
    </div>
</template>

<script>
export default {
    name: "InputPercent",
    props: {
        modelValue: [Object,Number]
    }
}
</script>

<style scoped>

</style>
