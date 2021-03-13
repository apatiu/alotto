<template>
    <button :type="type" :class="classObject">
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: 'button',
        },
        color: {
            type: String,
            default: ''
        }
    },
    computed: {
        classObject() {
            return {
                'bg-blue-500 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded': true,
                'bg-gray-800': this.color === '',
                'bg-blue-500 hover:bg-blue-700 text-white': this.color === 'primary',
                'bg-gray-400': this.color === 'secondary',
            }
        }
    }
}
</script>
