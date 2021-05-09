<template>
    <div class="border p-4 bg-yellow-50 shadow">
        <div class="font-bold mb-3">ราคาทองคำแท่ง</div>
        <div class="p-field p-grid mb-1">
            <div class="p-inputgroup">
                <span class="p-inputgroup-addon bg-red-200 w-20 font-bold text-sm">ขายออก</span>
                <InputNumber v-model="goldPrices.ask" inputClass="w-full text-right" disabled></InputNumber>
            </div>
        </div>
        <div class="p-field p-grid mb-1">
            <div class="p-inputgroup">
                <span class="p-inputgroup-addon bg-green-200 w-20 font-bold text-sm">รับซื้อ</span>
                <InputNumber v-model="goldPrices.bid" inputClass="w-full text-right" disabled></InputNumber>
            </div>
        </div>
    </div>
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
