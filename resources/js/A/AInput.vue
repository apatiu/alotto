<template>
    <div class="p-field">
        <label v-if="label">{{ label }}</label>
        <InputText :modelValue="modelValue"
                   @update:modelValue="$emit('update:modelValue', $event)"
                   v-bind="$attrs"
                   :class="{'p-invalid': errors.length}"
        ></InputText>
        <input-error :model-value="errors"></input-error>
    </div>
</template>

<script>

import InputError from "@/Jetstream/InputError";

export default {
    components: {InputError},
    inheritAttrs: false,
    props: {
        modelValue: String,
        label: String,
        errors: {type: [Object, String], default: []},
    }
}
</script>
