<template>
    <Tag :severity="severity" :value="status"></Tag>
</template>

<script>
export default {
    name: "StockImportStatus",
    props: ['value'],
    data: () => ({
       severity: 'success'
    }),
    computed: {
        status() {
            switch (this.value) {
                case 'draft':
                    this.severity = 'warning';
                    return 'ฉบับร่าง';
                case 'approved':
                    this.severity = 'success';
                    return 'อนุมัติแล้ว'
            }
        }
    }
}
</script>

<style scoped>

</style>
