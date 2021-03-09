<template>
    <jet-label v-if="label" :for="id">{{ label }}:</jet-label>
    <jet-input :id="id"
               :type="type"
               class="w-full"
               :value="modelValue"
               @update:modelValue="$emit('update:modelValue', $event)"/>
    <input-error :message="error"></input-error>

</template>

<script>
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import InputError from "@/Jetstream/InputError";

export default {
    components: {InputError, JetInput, JetLabel},
    inheritAttrs: false,
    props: {
        id: {
            type: String,
        },
        type: {
            type: String,
            default: 'text',
        },
        modelValue: String,
        label: String,
        error: String,
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
