<template>
    <div class="p-field">
        <label v-if="label">{{ label }}</label>
        <InputNumber :modelValue="modelValue"
                     @update:modelValue="$emit('update:modelValue', $event)"
                     v-bind="$attrs"
                     mode="decimal"
                     :disabled="disabled"
                     :readonly="readonly"
        ></InputNumber>
        <input-error :model-value="errors"></input-error>
    </div>
</template>

<script>

import InputError from "@/Jetstream/InputError";
export default {
    components: {InputError},
    inheritAttrs: false,
    props: {
        type: {
            type: String,
            default: 'text',
        },
        modelValue: {default: null},
        label: String,
        errors: {default: null},
        readonly: {type: Boolean, default: false},
        disabled: {type: Boolean, default: false}
    }
}
</script>
