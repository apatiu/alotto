<template>
    <small v-for="error in modelValue" class="p-error" v-show="type==='object'">
        {{ error.$message ?? error }}
    </small>
    <small class="p-error" v-show="type==='string'">
        {{ modelValue }}
    </small>
</template>

<script>
export default {
    props: ['modelValue'],
    computed: {
        type() {
            return typeof this.modelValue
        }
    }
}
</script>
