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
                'text-white font-bold py-2 px-4 rounded': true,
                'bg-gray-800': this.color === '',
                'bg-blue-500 hover:bg-blue-700': this.color === 'primary',
                'bg-gray-400 hover:bg-gray-700': this.color === 'secondary',
            }
        }
    }
}
</script>
