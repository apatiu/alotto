<template>
    <div :class="classObject">{{ title }}</div>
</template>

<script>
export default {
    name: "SavingStatus",
    props: {
        modelValue: String,
        small: {type: Boolean, default: false}
    },
    computed: {
        classObject() {
            return {
                'whitespace-nowrap rounded-3xl w-full text-center font-bold' : true,
                'py-1 text-sm small': this.small,
                'bg-green-100': this.modelValue === 'open',
                'bg-gray-100': this.modelValue === 'close',
            }
        },
        title() {
            switch (this.modelValue) {
                case 'open':
                    return 'เปิด';
                case 'close':
                    return 'ปิด';
            }
        }
    }
}
</script>

<style scoped>

</style>
