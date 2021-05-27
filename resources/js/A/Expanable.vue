<template>
    <div>
        <div @click="open = ! open" :class="[triggerClass]">
            <slot name="trigger"></slot>
        </div>
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <div v-show="open"
                 class="mt-2"
                 :class="[alignmentClasses]"
                 style="display: none;">
                <div :class="contentClasses">
                    <slot name="content"></slot>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import {onMounted, onUnmounted, ref} from "vue";

export default {
    name: 'Expanable',
    props: {
        align: {
            default: 'right'
        },
        triggerClass: {
            default: () => ['inline-block']
        },
        contentClasses: {
            default: () => []
        }
    },

    setup() {
        let open = ref(false)

        return {
            open,
        }
    },

    computed: {
        widthClass() {
            return {
                '48': 'w-48',
            }[this.width.toString()]
        },

        alignmentClasses() {
            if (this.align === 'left') {
                return 'origin-top-left left-0'
            } else if (this.align === 'right') {
                return 'origin-top-right right-0'
            } else {
                return 'origin-top'
            }
        },
    }
}
</script>
