<template>
    <div :class="classObject">{{ title }}</div>
</template>

<script>
export default {
    name: "PawnStatus",
    props: {
        modelValue: String,
        small: {type: Boolean, default: false}
    },
    computed: {
        classObject() {
            return {
                'whitespace-nowrap rounded-3xl w-full text-center font-bold py-2' : true,
                'py-1 text-sm small': this.small,
                'bg-green-100': this.modelValue === 'new',
                'bg-green-400 text-white': this.modelValue === 'int',
                'bg-gray-100': this.modelValue === 'red',
                'bg-yellow-100': this.modelValue === 'chg',
                'border': this.modelValue === 'cut',
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
                case 'chg':
                    return 'เปลี่ยนใบ';
                case 'cut':
                    return 'คัดออก';
            }
        }
    }
}
</script>

<style scoped>

</style>
