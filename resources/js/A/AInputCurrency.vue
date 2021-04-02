<template>
    <div class="p-field"></div>
        <InputNumber :modelValue="modelValue"
                   @update:modelValue="$emit('update:modelValue', $event)"
                   v-bind="$attrs"
                     mode="decimal"
                   :disabled="disabled"
                   :readonly="readonly"
        ></InputNumber>
        <label v-if="label">{{ label }}</label>
    <small class="p-error">{{ error }}</small>
</template>

<script>

export default {
    inheritAttrs: false,
    props: {
        type: {
            type: String,
            default: 'text',
        },
        modelValue: String,
        label: String,
        error: String,
        readonly: {type: Boolean, default: false},
        disabled: {type: Boolean, default: false}
    }
}
</script>
