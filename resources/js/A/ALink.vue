<template>
    <inertia-link :class="classObject"
                  :href="route(href)">
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: {
            href: String,
            color: {
                type: String,
                default: ''
            }
        },
        computed: {
            classObject() {
                return {
                    'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded': true,
                    'bg-gray-800': this.color === '',
                    'bg-blue-500 hover:bg-blue-700 text-white': this.color === 'primary',
                    'bg-gray-400': this.color === 'secondary',
                }
            }
        }
    }
</script>
