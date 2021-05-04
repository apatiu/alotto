<template>
    <div class="p-flex border-b">
        <div class="cell-gold-price">{{ goldprice }}</div>
    </div>
    <div class="p-grid p-mt-2">
        <div class="p-col-8">
            <div class="p-flex-column">
                <div class="cell-sell">
                    <div class="card">
                        <div class="p-flex-column">
                            <h5>ขาย</h5>
                            <div>
                                <select-product
                                    @select="onSelectProduct($event)"
                                ></select-product>
                                <Button label="S"></Button>
                            </div>
                            <DataTable
                                :value="form.sales"
                                class="p-datatable-sm p-mt-1">
                                <Column field="product_id" header="รหัส" frozen class="w-40"></Column>
                                <Column field="product_name" header="ชื่อสินค้า" class="w-40"></Column>
                                <Column field="qty" header="จำนวน" class="w-20">
                                    <template #footer>
                                        {{ salesQtySum }}
                                    </template>
                                </Column>
                                <Column field="wt" header="นน.รวม" class="w-20"></Column>
                                <Column field="price_sale_gold" header="ราคาทอง" class="w-20"></Column>
                                <Column field="price_sale_wage" header="ค่าแรง" class="w-20">
                                    <template #footer>
                                        {{ salesTagWageSum }}
                                    </template>
                                </Column>
                                <Column field="discount" header="ส่วนลด" class="w-20"></Column>
                                <Column field="deposit" header="มัดจำ" class="w-20"></Column>
                                <Column field="price_sale_total" header="รวม" class="w-20">
                                    <template #footer>
                                        {{ salesPriceSaleTotalSum }}
                                    </template>
                                </Column>
                                <Column field="change_price" header="ราคาเปลี่ยน" class="w-20"></Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
                <div class="cell-buy mt-2">
                    <div class="card p-flex-column">
                        <h5>ซื้อ</h5>
                        <div class="flex space-x-1">
                            <div class="w-24">
                                <select-gold-percent v-model="buy.product_percent_id"></select-gold-percent>
                            </div>
                            <div class="w-40">
                                <select-product-type v-model="buy.product_type"></select-product-type>
                            </div>
                            <div class="w-24">
                                <label for="">น้ำหนัก</label>
                                <InputNumber v-model="buy.wt"
                                             :minFractionDigits="2"
                                             input-class="w-full"></InputNumber>
                            </div>
                            <div class="w-40">
                                <label for="">ราคารรับซื้อ</label>
                                <InputNumber v-model="buy.price_buy_total"
                                             input-class="w-full"/>
                            </div>
                            <div class="w-20 flex items-end">
                                <Button icon="pi pi-plus" @click="addBuy"></Button>
                            </div>
                        </div>
                        <DataTable :value="form.buys" class="p-mt-1">
                            <Column field="product_percent_id" header="%" frozen footer=""></Column>
                            <Column field="product_type" header="สินค้า" footer=""></Column>
                            <Column field="wt" header="นน.รวม"></Column>
                            <Column field="price_buy_calc" header="ราคาคำนวณ">
                                <template #footer>
                                    {{ buysPriceBuyCalcSum }}
                                </template>
                            </Column>
                            <Column field="price_buy_total" header="ราคารับซื้อ">
                                <template #footer>
                                    {{ buysPriceBuyTotalSum }}
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-col-4">
            <div class="p-flex-column">
                <div class="cell-cust">
                    <select-customer v-model="form.customer"></select-customer>
                </div>
                <div class="cell-bill bg-yellow-50 shadow p-4">
                    <p class="text-center font-bold text-sm">{{ billTotal }}</p>
                </div>
                <Button label="รับชำระเงิน" class="p-button-lg w-full"></Button>
            </div>
        </div>
    </div>
</template>

<script>
import SelectGoldPercent from "@/A/SelectGoldPercent";
import InputWeight from "@/A/InputWeight";
import SelectProductType from "@/A/SelectProductType";
import SelectProduct from "@/A/SelectProduct";

export default {
    name: "Pos",
    components: {SelectProduct, SelectProductType, InputWeight, SelectGoldPercent},
    props: {
        bill: Object,
        customer: Object,
    },
    data() {
        return {
            form: this.$inertia.form({}),
            goldprice: 0,
            product: null,
            buy: {
                product_percent_id: 96,
                product_type: '',
                wt: 0,
                price_buy_calc: 0,
                price_buy_total: 0,
            }
        }
    },
    watch: {
        'buy.wt': function (val) {
            let self = this;
            this.calcBuyPrice(val)
                .then((data) => {
                    self.buy.price_buy_calc = Math.round(data)
                    self.buy.price_buy_total = Math.round(data)
                })
        }
    },
    computed: {
        salesQtySum() {
            let output = _.sumBy(this.form.sales, (o) => {
                return o.qty;
            })
            return output
        },
        salesTagWageSum() {
            let o = _.sumBy(this.form.sales, (o) => {
                return o.price_sale_wage;
            })

            return o
        },
        salesPriceSaleTotalSum() {
            let o = _.sumBy(this.form.sales, (o) => {
                return o.price_sale_total;
            })

            this.form.total_price_sale = o
            return o
        },
        buysWtSum() {
            let o = _.sumBy(this.form.buys, (o) => {
                return o.wt;
            })

            this.form.total_wt_buy = o
            return o
        },
        buysPriceBuyCalcSum() {
            let o = _.sumBy(this.form.buys, (o) => {
                return o.price_buy_calc;
            })

            this.form.total_price_buy = o
            return o
        },
        buysPriceBuyTotalSum() {
            let o = _.sumBy(this.form.buys, (o) => {
                return o.price_buy_total;
            })

            this.form.total_price_buy = o
            return o
        },
        billTotal() {
            this.form.total_amount = this.form.total_price_sale - this.form.total_price_buy
            return this.form.total_amount
        }
    },
    mounted() {
        if (!this.bill) {
            this.goldprice = this.getGoldPrice();
            this.create()
        }
    },
    methods: {
        create() {
            this.form = _.assign(this.form, {
                id: null,
                code: '',
                goldprice: this.goldprice,
                dt: new Date,
                sales: [],
                buys: [],
                total: 0,
                status: 'open',
                customer: this.customer
            })
        },
        getGoldPrice() {
            axios.get(route('api.goldprice'))
                .then(({data}) => {
                    this.goldprice = data
                })
        },
        onSelectProduct(e) {
            let sale = this.getSaleLine(e, 1)
            this.form.sales.push(sale);

        },
        onInputProduct(e) {
            this.getSaleLine(e.gold_percent, e.wt)
        },
        getSaleLine(e, qty = 1) {
            let sale = {}
            if (e.sale_with_gold_price) {

                let wtGram = e.weightbaht ? e.weight * 15.2 : e.weight;
                let priceSaleGold = numeral(this.goldprice)
                    .add(e.gold_percent.add_sale)
                    .multiply(e.gold_percent.percent_sale / 100)
                    .multiply(wtGram)
                    .divide(15.2)
                    .value();

                sale.price_sale_gold = priceSaleGold
                sale.product_wt = wtGram
                sale.cost_wage = e.cost_wage
                sale.tag_wage = e.tag_wage
                sale.avg_cost_per_baht = e.avg_cost_per_baht
                sale.wt = qty * wtGram
                sale.price_sale_wage = qty * sale.tag_wage
                sale.price_sale_total = numeral(sale.price_sale_gold)
                    .add(sale.price_sale_wage)
                    .value()
                sale.profit_wage = sale.price_sale_wage - (e.cost_wage * qty)
                sale.profit_gold = sale.price_sale_gold - (sale.avg_cost_per_baht)
                sale.profit_total = numeral(sale.profit_wage).add(sale.profit_gold)
            } else {

            }

            sale.qty = qty
            sale.status = 'sale'
            sale.product_id = e.product_id
            sale.product_type = e.product_type
            sale.product_design = e.product_design
            sale.product_size = e.product_size
            sale.prodcut_name = e.name
            return sale
        },
        getPriceSaleGold(pp, w) {
            let productPercent = null
            let price = 0;
            axios.get(route('api.product-percents.show', pp))
                .then(({data}) => {
                    productPercent = data;
                    console.log(productPercent)
                    let p = numeral(productPercent)
                    return p
                })

        },
        getGoldPercent(id) {
            return axios.get(route('api.product-percents.show', id))
                .then(({data}) => {
                    return data;
                })
        },
        addRawProduct(e) {

        },
        async calcBuyPrice(wt) {
            let gPercent = await this.getGoldPercent(this.buy.product_percent_id)
            let o = numeral(this.goldprice)
                .subtract(gPercent.deduct_buy)
                .multiply(gPercent.percent_buy / 100)
                .multiply(wt)
                .divide(15.2)
                .value()

            o = numeral(o).multiply(1 - (gPercent.percent_deduct_total_buy / 100)).value();
            return o
        },
        addBuy() {
            let buy = _.assign({}, this.buy, {
                status: 'buy'
            })
            buy.product_type = this.buy.product_type.name
            this.form.buys.push(buy)
        },
        update() {
            this.form.post(route('sales.store'))
        }
    }
}
</script>

<style scoped>

</style>
