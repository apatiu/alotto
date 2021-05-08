<template>
    <Card>
        <template #title>ราคาทองคำแท่ง</template>
        <template #content>
                <div class="p-field p-grid">
                    <label for="" class="p-col-fixed w-20">ขายออก</label>
                    <div class="p-col">
                        <InputNumber v-model="goldPrices.ask"></InputNumber>
                    </div>
                </div>
                <div class="p-field p-grid">
                    <label for="" class="p-col-fixed w-20">รับซื้อ</label>
                    <div class="p-col">
                        <InputNumber v-model="goldPrices.bid"></InputNumber>
                    </div>
                </div>
        </template>
    </Card>
</template>

<script>
export default {
    name: "GoldPrices",
    data() {
        return {
            goldPrices: {}
        }
    },
    created() {
        axios.get(route('api.gold-prices.now'))
            .then(({data}) => {
                this.goldPrices = data
            })
    }
}
</script>

<style scoped>

</style>
