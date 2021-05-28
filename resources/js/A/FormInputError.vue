<template>
    <small class="p-error" v-if="message">
        {{ message }}
    </small>
</template>

<script>
export default {
    name: 'FormInputError',
    props: {message: {default: null}},
    computed: {
        type() {
            return typeof this.modelValue
        }
    }
}
</script>
