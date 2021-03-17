<template>
    <span class="p-float-label p-filled">
        <InputNumber :modelValue="modelValue"
                   @update:modelValue="$emit('update:modelValue', $event)"
                   v-bind="$attrs"
                   :disabled="disabled"
                   :readonly="readonly"
        ></InputNumber>
        <label v-if="label">{{ label }}</label>
    </span>
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
