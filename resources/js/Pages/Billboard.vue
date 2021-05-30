<template>
    <div class="flex h-full sm:justify-center items-center bg-gray-900 overflow-hidden scroll"
         style="background-image: url('/images/bg-billboard.jpg');">
        <div class="flex flex-col items-center justify-center">
            <div class="px-10">
                <img src="/images/logo-default.png" alt="">
            </div>
            <div class="font-prompt text-4xl">{{ diffCaption }}</div>
            <div class="font-oswald text-9xl flex items-center" :class="classDiffNumber">
                <i :class="iconDiffNumber" class="text-5xl"></i>
                <div>{{ diffNumber }}</div>
            </div>
        </div>
        <div class=" flex flex-col justify-center overflow-hidden" style="height: 100vh">

            <div class="text-7xl text-black text-center"
                 style="font-family: Prompt; text-shadow: 2px 2px #cccccc">ราคาทองวันนี้
            </div>


            <div class="bg-red-900 text-white shadow text-4xl px-8 py-2 rounded-3xl text-center mx-auto my-4"
                 style="font-family: Prompt">
                {{ $filters.datetime(goldprice.updated_at) }}
            </div>
            <div style="background-color: rgba(255,255,255,0.3)" class="py-4 px-10 rounded">

                <div class="flex flex-col space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-end price-title">
                            <div class="text-6xl font-prompt whitespace-nowrap">ทองคำแท่ง</div>
                            <div class="text-5xl font-prompt text-red-500">ขายออก</div>
                        </div>
                        <div class="text-red-700"
                             style="font-size: 15vh; line-height: 18vh; font-family: Oswald">
                            {{ $filters.decimal(goldprice.gold_price_sale) }}
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-end price-title">
                            <div class="text-6xl font-prompt whitespace-nowrap">ทองคำแท่ง</div>
                            <div class="text-5xl font-prompt text-red-500">ซื้อเข้า</div>
                        </div>
                        <div class="text-red-700"
                             style="font-size: 15vh; line-height: 18vh; font-family: Oswald">
                            {{ $filters.decimal(goldprice.gold_price_buy) }}
                        </div>
                    </div>
                    <Divider style="border-bottom: 1px solid black;"/>
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-end price-title">
                            <div class="text-6xl font-prompt text-yellow-700 whitespace-nowrap">ทองรูปพรรณ</div>
                            <div class="text-5xl font-prompt text-red-500">รับซื้อ</div>
                        </div>
                        <div class="text-red-700"
                             style="font-size: 15vh; line-height: 18vh; font-family: Oswald">
                            {{ $filters.decimal(goldprice.gold_price_buy * 0.94) }}
                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Billboard",
    data() {
        return {
            goldprice: {}
        }
    },
    computed: {
        diffCaption() {
            if (this.goldprice.gold_price_diff > 0) {
                return 'ปรับขึ้น'
            } else if (this.goldprice.gold_price_diff < 0) {
                return 'ปรับลง'
            } else {
                return 'ไม่เปลี่ยนแปลง'
            }
        },
        diffNumber() {
            return this.goldprice.gold_price_diff === 0 ? '-' : Math.abs(this.goldprice.gold_price_diff)
        },
        classDiffNumber() {
            return {
                'text-green-600': this.diffNumber >= 0,
                'text-red-600' : this.diffNumber < 0
            }
        },
        iconDiffNumber() {
            return {
                'pi pi-caret-up': this.diffNumber > 0,
                'pi pi-caret-down': this.diffNumber < 0
            }
        }

    },
    mounted() {
        this.loadGoldPrice()
        setInterval(function () {
            this.loadGoldPrice
        }, 300000)
    },
    methods: {
        loadGoldPrice() {
            axios.get(route('api.gold-prices.now'))
                .then(({data}) => {
                    this.goldprice = data
                })
        }
    }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Prompt:wght@700&display=swap');

.font-prompt {
    font-family: Prompt;
}

.font-oswald {
    font-family: Oswald;
}
.price-title {
    max-width: 350px;
    padding-right: 40px;
    width: 100%;
}
.p-divider-solid.p-divider-horizontal:before {
    border-top: 1px solid black;
}
</style>
