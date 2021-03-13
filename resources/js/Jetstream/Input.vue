<template>
    <input
        class="border-gray-300
        focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
        disabled:opacity-50
        rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :readonly="readonly"
        :disabled="disabled">
</template>

<script>
export default {
    props: {
        'modelValue': {default: null},
        readonly: {type: Boolean, default: false},
        disabled: {type: Boolean, default: false},
    },

    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus()
        }
    }

}
</script>

