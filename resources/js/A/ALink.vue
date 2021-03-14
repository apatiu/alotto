<template>
    <inertia-link :class="classObject"
                  :href="route(href)"
                  :as="as">
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: {
            as: {type: String, default: 'a'},
            href: String,
            color: {
                type: String,
                default: ''
            }
        },
        computed: {
            classObject() {
                return {
                    'text-white font-bold py-2 px-4 mx-1 rounded': true,
                    'bg-gray-800': this.color === '',
                    'bg-blue-500 hover:bg-blue-700': this.color === 'primary',
                    'bg-gray-400 hover:bg-gray-700': this.color === 'secondary',
                }
            }
        }
    }
</script>
