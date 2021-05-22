<template>
    <a-label v-if="label">{{ label }}:</a-label>
    <InputText :type="type"
               class="w-full"
               :value="modelValue"
               :readonly="readonly"
               :disabled="disabled"
               @update:modelValue="$emit('update:modelValue', $event)"/>
    <input-error :message="error"></input-error>

</template>

<script>
import JetInput from '@/Jetstream/Input'
import ALabel from "@/A/ALabel"
import InputError from "@/Jetstream/InputError";

export default {
    components: {InputError, JetInput, ALabel},
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
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
        emit($event) {
            this.$emit('input', $event.target.value);
            console.log($event.target.value)
        }
    },
}
</script>
