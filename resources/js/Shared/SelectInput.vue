<template>
    <label v-if="label" :for="id">{{ label }}:</label>
    <select :id="id" ref="input"
            v-model="selected" v-bind="$attrs"
            class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            :class="{ error: error }">
        <slot/>
    </select>
    <input-error :message="error"></input-error>
</template>

<script>
import InputError from "@/Jetstream/InputError";

export default {
    components: {InputError},
    inheritAttrs: false,
    props: {
        id: {
            type: String,
        },
        value: [String, Number, Boolean],
        label: String,
        error: String,
    },
    data() {
        return {
            selected: this.value,
        }
    },
    watch: {
        selected(selected) {
            this.$emit('input', selected)
        },
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
    },
}
</script>
