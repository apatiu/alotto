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
            default: 'submit',
        },
        color: {
            type: String,
            default: ''
        }
    },
    computed: {
        classObject() {
            return {
                'inline-flex items-center px-4 py-2 border border-transparen rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150': true,
                'bg-gray-800': this.color === '',
                'bg-blue-500 hover:bg-blue-700 text-white': this.color === 'primary',
                'bg-gray-400': this.color === 'secondary',
            }
        }
    }
}
</script>
