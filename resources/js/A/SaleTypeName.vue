<template>
    <span>{{ label }}</span>
</template>

<script>
export default {
    name: "SaleTypeName",
    props: ['modelValue'],
    computed: {
        label() {
            switch (this.modelValue) {
                case 'sale' :
                    return 'ขาย'
                case 'buy' :
                    return 'ซื้อ'
                case 'change' :
                    return 'เปลี่ยน'
            }
        }
    }
}
</script>

<style scoped>

</style>
