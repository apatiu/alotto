<template>
    <div :class="classObject">{{ title }}</div>
</template>

<script>
export default {
    name: "PawnStatus",
    props: {
        modelValue: String,
        badge: {type: Boolean, default: false}
    },
    computed: {
        classObject() {
            return {
                'px-6 py-2 rounded font-bold': this.badge,
                'bg-green-100': this.modelValue === 'new',
                'bg-gray-100': this.modelValue === 'red',
            }
        },
        title() {
            switch (this.modelValue) {
                case 'new':
                    return 'ใหม่';
                case 'int':
                    return 'ต่อดอก';
                case 'red':
                    return 'ไถ่ถอน';
            }
        }
    }
}
</script>

<style scoped>

</style>
