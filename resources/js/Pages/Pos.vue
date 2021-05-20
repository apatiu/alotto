<template>

    <div class="layout-topbar type-bar pb-4">
        <div class="flex justify-between w-full">
            <div>
                <SelectButton v-model="form.type"
                              :options="types"
                              optionLabel="label"
                              optionValue="value"
                              :disabled="form.status==='checked'">
                    <template #option="slotProps">
                        <div class="type-options w-24">
                            <div>{{ slotProps.option.label }}</div>
                        </div>
                    </template>
                </SelectButton>
            </div>
            <div class="flex space-x-2">
                <div>
                    <smartbar-pawn></smartbar-pawn>
                </div>
                <div>
                    <smartbar-saving></smartbar-saving>
                </div>
                <div>
                    <Button class="p-button-secondary"
                            @click="dlgBuyLottery=true">ซื้อรางวัล
                    </Button>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-24"></div>

    <template v-if="form.type">
        <div class="p-grid">
            <div class="p-col-9">
                <div class="flex justify-between">
                    <div class="p-inputgroup w-60">
                        <span class="p-inputgroup-addon" for="">รหัส</span>
                        <InputText v-model="form.code" :disabled="checked"/>
                    </div>
                    <div class="p-inputgroup w-60">
                        <span class="p-inputgroup-addon">วันที่</span>
                        <Calendar v-model="form.dt"
                                  :disabled="checked"
                                  showTime
                                  hourFormat="24"
                                  :manualInput="false"/>
                    </div>
                </div>
                <div class="mt-2 p-grid">
                    <div class="p-col-9">
                        <select-customer
                            v-model="form.customer"
                            @update:modelValue="form.customer_id = $event.id"
                            class="bg-white"
                            :disabled="form.status==='checked'"></select-customer>
                    </div>
                    <div class="p-col-3">
                        <gold-prices></gold-prices>
                    </div>
                </div>

                <div class="cell-sell" v-if="form.type !== 'buy'">
                    <Card>
                        <template #title><span class="text-green-800">ขาย</span></template>
                        <template #content>
                            <div class="p-flex-column">
                                <select-product
                                    @select-product="onSelectProduct($event)"
                                    reset-after-select
                                    v-if="saleEditable"/>
                                <DataTable
                                    :value="form.sales"
                                    class="p-datatable-sm p-mt-1"
                                    editMode="cell"
                                    @cellEditComplete="onSalesCellEditComplete"
                                    :scrollable="true">
                                    <Column field="product_code" header="รหัส" frozen class="w-40"></Column>
                                    <Column field="product_name" header="ชื่อสินค้า"
                                            style="min-width:170px;"></Column>
                                    <Column field="qty" header="จำนวน" class="w-28 justify-center">
                                        <template #editor="slotProps">
                                            <InputNumber
                                                :modelValue="slotProps.data.qty"
                                                @update:modelValue="onSalesCellEdit($event,slotProps)"
                                                inputClass="w-full"
                                                showButtons
                                            ></InputNumber>
                                        </template>
                                        <template #footer>
                                            {{ salesQtySum }}
                                        </template>
                                    </Column>
                                    <Column field="wt" header="นน.รวม" class="w-20">
                                        <template #footer>
                                            {{ salesWtSum }}
                                        </template>
                                    </Column>
                                    <Column field="price_sale_gold" header="ราคาทอง" class="w-20"></Column>
                                    <Column field="price_sale_wage" header="ค่าแรง" class="w-20">
                                        <template #footer>
                                            {{ salesTagWageSum }}
                                        </template>
                                    </Column>
                                    <Column field="discount" header="ส่วนลด" class="w-20"></Column>
                                    <Column field="deposit" header="มัดจำ" class="w-20"></Column>
                                    <Column field="price_sale_total" header="รวม" class="w-20 justify-right">
                                        <template #editor="slotProps">
                                            <InputNumber
                                                :modelValue="slotProps.data.price_sale_total"
                                                @update:modelValue="onSalesCellEdit($event,slotProps)"
                                                inputClass="w-full"
                                            ></InputNumber>
                                        </template>
                                        <template #footer>
                                            {{ salesPriceSaleTotalSum }}
                                        </template>
                                    </Column>
                                    <Column field="change_price" header="ราคาเปลี่ยน" class="w-20"></Column>
                                    <Column headerClass="text-center" bodyClass="justify-center"
                                            style="width: 3rem">
                                        <template #body="{index}">
                                            <Button type="button"
                                                    icon="pi pi-trash"
                                                    class="p-button-sm p-button-rounded p-button-text p-button-plain"
                                                    @click="removeSale(index)"
                                                    :disabled="form.status==='checked'"></Button>
                                        </template>
                                    </Column>
                                    <template #empty>
                                        <InputError :model-value="v.form.sales.$errors"></InputError>
                                    </template>
                                </DataTable>
                            </div>
                        </template>
                    </Card>
                </div>
                <div class="cell-buy mt-2" v-if="form.type !== 'sale'">
                    <Card>
                        <template #title><span class="text-red-800">ซื้อ</span></template>
                        <template #content>
                            <div class="flex space-x-1">
                                <div class="w-24">
                                    <select-gold-percent v-model="buy.product_percent_id"
                                                         @select="buy.product_percent_name = $event.gold_percent"></select-gold-percent>
                                </div>
                                <div class="w-40">
                                    <select-product-type
                                        v-model="buy.product_type"></select-product-type>
                                </div>
                                <div class="w-24">
                                    <label>น้ำหนัก</label>
                                    <InputNumber v-model="buy.wt"
                                                 :minFractionDigits="2"
                                                 input-class="w-full"></InputNumber>
                                </div>
                                <div class="w-40">
                                    <label>ราคารับซื้อ</label>
                                    <InputNumber v-model="buy.price_buy_total"
                                                 input-class="w-full"/>
                                </div>
                                <div class="w-20 flex items-end">
                                    <Button icon="pi pi-plus"
                                            @click="addBuy"
                                            :disabled="v.buy.$invalid"></Button>
                                </div>
                            </div>
                            <DataTable :value="form.buys" class="p-datatable-sm p-mt-1">
                                <Column field="product_percent.gold_percent" header="%" frozen footer=""></Column>
                                <Column field="product_type_name" header="สินค้า" footer=""></Column>
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
                                <Column headerClass="text-center w-20">
                                    <template #body="{index}">
                                        <Button type="button"
                                                icon="pi pi-trash"
                                                class="p-button-sm p-button-rounded p-button-text p-button-plain"
                                                @click="removeBuy(index)"
                                                v-if="saleEditable"
                                        ></Button>
                                    </template>
                                </Column>
                                <template #empty>
                                    <InputError :model-value="v.form.buys.$errors"></InputError>
                                </template>
                            </DataTable>
                        </template>
                    </Card>
                </div>

                <div class="section-payments mt-8" v-if="form.payments.length">
                    <h5 class="h5">รายการชำระเงิน</h5>
                    <DataTable :value="form.payments">
                        <Column field="dt" header="วันที่">
                            <template #body="props">
                                {{ $filters.datetime(props.data.dt) }}
                            </template>
                        </Column>
                        <Column field="method.name" header="ช่องทาง"></Column>
                        <Column field="bank_account.name" header="บัญชี"></Column>
                        <Column field="receive" header="รับ"></Column>
                        <Column field="pay" header="จ่าย"></Column>
                    </DataTable>
                </div>
            </div>
            <div class="p-col-3 flex-col space-y-2">
                <div class="p-field">
                    <label>ยอดรวม</label>
                    <InputNumber :modelValue="billTotal"
                                 disabled
                                 :inputClass="['text-right w-full text-3xl font-bold',classBillTotal]"
                                 class="p-inputtext-lg opacity-100 border-black"></InputNumber>
                </div>
                <Button label="กำหนดค่าเปลี่ยน"
                        v-show="form.type==='change'"
                        class="w-full p-button-warning"
                        :disabled="form.status!=='open'"
                        @click="getChangePrice"></Button>
                <Button label="รับชำระเงิน"
                        class="p-button-lg w-full"
                        :disabled="form.type===null || form.status!=='open'"
                        @click="checkout"></Button>
                <div class="pt-20" v-if="checked && form.type!=='buy'">
                    <Button label="พิมพ์ใบรับประกัน"
                            @click="printGuaranteeCard"></Button>
                </div>
                <div class="pt-20">
                    <Button label="เริ่มใหม่"
                            class="p-button-secondary w-full"
                            @click="reset"></Button>
                </div>
            </div>
            <OverlayPanel ref="opChangePrice">
                <div class="p-fluid">
                    <div class="p-field">
                        <label>ค่าเปลี่ยน</label>
                        <InputNumber
                            v-model="form.sales[0].change_price"
                            ref="edChangePrice"></InputNumber>
                    </div>
                    <Button label="ตกลง" @click="setChangePrice"></Button>
                </div>
            </OverlayPanel>
        </div>

    </template>
    <input-payment v-model:visible="paymentDialog"
                   :target="form.total_amount"
                   @done="savePayments($event)"></input-payment>
    <buy-lottery v-model:visible="dlgBuyLottery"></buy-lottery>

</template>

<script>
import SelectGoldPercent from "@/A/SelectGoldPercent";
import InputWeight from "@/A/InputWeight";
import SelectProductType from "@/A/SelectProductType";
import SelectProduct from "@/A/SelectProduct";
import SelectCustomer from "@/A/SelectCustomer";
import InputPayment from "@/A/InputPayment";
import useVuelidate from '@vuelidate/core'
import {required, requiredIf, minLength} from '@vuelidate/validators'
import GoldPrices from "@/A/GoldPrices";
import InputError from "@/Jetstream/InputError";
import ACalendar from "@/A/ACalendar";
import SmartbarPawn from "@/A/SmartbarPawn";
import SmartbarSaving from "@/A/SmartbarSaving";
import {mapValues} from "lodash";
import BuyLottery from "@/A/BuyLottery";

export default {
    name: "Pos",
    setup() {
        const v = useVuelidate()
        return {v}
    },
    components: {
        BuyLottery,
        SmartbarSaving,
        SmartbarPawn,
        ACalendar,
        InputError,
        GoldPrices,
        InputPayment,
        SelectCustomer,
        SelectProduct,
        SelectProductType, InputWeight, SelectGoldPercent
    },
    props: {
        bill: Object,
        customer: Object,
    },
    data() {
        return {
            goldprice: null,
            types: [
                {label: 'ขาย', icon: 'pi pi-arrow-up', value: 'sale'},
                {label: 'ซื้อ', icon: 'pi pi-arrow-up', value: 'buy'},
                {label: 'เปลี่ยน', icon: 'pi pi-arrow-up', value: 'change'}
            ],
            form: this.$inertia.form({
                id: null,
                code: null,
                dt: null,
                gold_price_sale: 0,
                gold_price_buy: 0,
                gold_price_tax: 0,
                customer: this.customer,
                customer_id: null,
                customer_name: null,
                customer_phone: null,
                customer_tax_id: null,
                total_price_sale: 0,
                total_price_buy: 0,
                total_amount: 0,
                total_wt_sale: 0,
                total_wt_buy: 0,
                total_qty_sale: 0,
                product_cost_price: 0,
                total_deposit: 0,
                note: null,
                type: null,
                status: 'open',
                sales: [],
                buys: [],
                payments: []

            }),
            product: null,
            buy: {
                product_percent_id: 96,
                product_percent: null,
                product_type: '',
                wt: 0,
                price_buy_calc: 0,
                price_buy_total: 0,
                avg_cost_per_baht: 0,
            },
            paymentDialog: false,
            editingSalesCellRows: {},
            editingBuysCellRows: {},
            dlgBuyLottery: false
        }
    },
    validations() {
        return {
            form: {
                type: {required},
                customer: {required},
                sales: {
                    required: requiredIf(() => {
                        return this.form.type !== "buy"
                    })
                },
                buys: {
                    required: requiredIf(() => {
                        return this.form.type !== "sale"
                    })
                }
                // buys: {
                //     minLength: minLength(this.form.type !== 'sales' ? 1 : 0)
                // }
            },
            buy: {
                product_type: {required},
                wt: {required}
            }
        }
    },
    watch: {
        'form.type': function (val) {
            if (val === 'sale') {
                this.form.buys = []
            } else if (val === 'buy') {
                this.form.sales = []
            }
        },
        'buy.wt':
            function (val) {
                let self = this;
                this.calcBuyPrice(val)
                    .then((data) => {
                        self.buy.price_buy_calc = Math.round(data)
                        self.buy.price_buy_total = Math.round(data)
                    })
            }
    },
    computed: {
        checked() {
            return this.form.status === 'checked'
        },
        saleEditable() {
            return this.form.status === 'open'
        },
        salesQtySum() {
            let output = _.sumBy(this.form.sales, (o) => {
                return o.qty;
            })
            return output
        },
        salesWtSum() {
            let o = _.sumBy(this.form.sales, (o) => {
                return o.wt;
            })

            this.form.total_wt_sale = o
            return o
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
        },
        classBillTotal() {
            return {
                'text-blue-700': this.form.total_amount >= 0,
                'text-red-700': this.form.total_amount < 0
            }
        }
    },
    mounted() {
        let d = '2021-05-12 05:05:42'
        if (!this.bill) {
            this.reset()
        } else {
        }
    },
    methods: {
        resetBuy() {
            this.buy = mapValues(this.buy, () => null)
            this.buy.product_percent_id = 96;
        },
        reset() {
            this.form.reset();
            this.form.customer_id = this.customer.id
            this.form.dt = new Date();
            this.form.sales = [];
            this.form.buys = [];

            this.$nextTick(() => {
                this.getGoldPrice();
            })

        },
        getGoldPrice() {
            axios.get(route('api.gold-prices.now'))
                .then(({data}) => {
                    this.goldprice = data;
                    this.form.gold_price_sale = data.gold_price_sale;
                    this.form.gold_price_buy = data.gold_price_buy;
                    this.form.gold_price_tax = data.gold_price_tax;
                })
        },
        onSelectProduct(e) {
            this.product = null;
            console.log('Select product : ')
            console.log(e)
            if (e) {
                let sale = this.getSaleLine(e, 1)
                this.form.sales.push(sale);
            }
        },
        onInputProduct(e) {
            this.getSaleLine(e.gold_percent, e.wt)
        },
        getSaleLine(e, qty = 1) {
            let sale = {}
            if (e.sale_with_gold_price) {

                let wtGram = e.weightbaht ? e.weight * 15.2 : e.weight;
                let priceSaleGold = this.getPriceSaleGold(e.gold_percent, wtGram);

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
                sale.profit_total = numeral(sale.profit_wage).add(sale.profit_gold).value()
                sale.sale_with_gold_price = e.sale_with_gold_price
                sale.wage_by_pcs = e.wage_by_pcs
            } else {

            }

            sale.qty = qty
            sale.status = 'sale'
            sale.product_id = e.id
            sale.product_code = e.code
            sale.product_type = e.product_type
            sale.product_design = e.product_design
            sale.product_size = e.product_size
            sale.product_name = e.name
            sale.gold_percent = e.gold_percent
            sale.discount = sale.deposit = 0
            return sale
        },
        getPriceSaleGold(goldPercent, wtGram, gold_price_sale = null) {
            let goldprice = gold_price_sale ?? this.form.gold_price_sale;
            let priceSaleGold = numeral(goldprice)
                .add(goldPercent.add_sale)
                .multiply(goldPercent.percent_sale / 100)
                .multiply(wtGram)
                .divide(15.2)
                .value();
            return Math.floor(priceSaleGold);
        },
        getGoldPercent(id) {
            return axios.get(route('api.product-percents.show', id))
                .then(({data}) => {
                    return data;
                })
        },
        addRawProduct(e) {

        },
        onSalesCellEditComplete(e) {
            if (!this.editingSalesCellRows[e.index])
                return;

            const editingCellValue = this.editingSalesCellRows[e.index][e.field];

            let editingRow = this.editingSalesCellRows[e.index];
            switch (e.field) {
                case 'qty':
                    if (editingRow.sale_with_gold_price) {
                        editingRow.wt = numeral(editingRow.product_wt).multiply(editingRow.qty).value();
                        editingRow.price_sale_gold = this.getPriceSaleGold(editingRow.gold_percent, editingRow.wt, editingRow.gold_price);
                        editingRow.price_sale_wage = editingRow.qty * editingRow.tag_wage;
                        editingRow.price_sale_total =
                            numeral(editingRow.price_sale_gold)
                                .add(editingRow.price_sale_wage)
                                .subtract(editingRow.discount)
                                .subtract(editingRow.deposit).value()
                    } else {
                        editingRow.price_sale_total =
                            numeral(editingRow.qty)
                                .add(editingRow.tag_price)
                                .subtract(editingRow.discount)
                                .subtract(editingRow.deposit).value()
                    }
                    this.form.sales[e.index] = editingRow
                    break;
                case 'price_sale_total':
                    if (editingRow.sale_with_gold_price) {
                        let oldVal = numeral(editingRow.price_sale_wage).add(editingRow.price_sale_gold).value();
                        if (editingCellValue > oldVal) {
                            editingRow.price_sale_wage = editingCellValue - editingRow.price_sale_gold
                            editingRow.discount = 0
                        } else {
                            editingRow.price_sale_wage = oldVal - editingRow.price_sale_gold
                            editingRow.discount = oldVal - editingCellValue
                        }
                    }

                    this.form.sales[e.index] = editingRow
                    break;
            }
        },
        onSalesCellEdit(val, props) {
            if (!this.editingSalesCellRows[props.index]) {
                this.editingSalesCellRows[props.index] = {...props.data}
            }

            this.editingSalesCellRows[props.index][props.column.props.field] = val;
        },
        removeSale(i) {
            this.form.sales.splice(i, 1)
        },
        removeBuy(i) {
            this.form.buys.splice(i, 1)
        },
        async calcBuyPrice(wt) {
            if (!this.buy.product_percent_id && !wt) return

            let gPercent = await this.getGoldPercent(this.buy.product_percent_id)
            this.buy.product_percent = gPercent;

            let o = numeral(this.form.gold_price_sale)
                .subtract(gPercent.deduct_buy)
                .multiply(gPercent.percent_buy / 100);

            o.multiply(wt).divide(15.2);
            o.multiply(1 - (gPercent.percent_deduct_total_buy / 100));
            return o.value()
        },
        addBuy() {
            if (this.v.buy.$invalid) return
            let buy = _.assign({}, this.buy, {
                status: 'buy',
                avg_cost_per_baht: (this.buy.price_buy_total / this.buy.wt) * 15.2
            })
            buy.product_type_name = this.buy.product_type.name
            this.form.buys.push(buy)
            this.resetBuy()
        },
        getChangePrice(e) {
            this.v.$reset()
            this.v.$touch()

            this.$nextTick(() => {
                if (this.v.form.sales.$error || this.v.form.buys.$error) {
                    this.form.change_price = null
                    return
                }

                this.form.change_price = 0;
                this.$refs.opChangePrice.show(e)
            })

        },
        setChangePrice(e) {
            this.$refs.opChangePrice.hide()
            let diff = this.form.total_amount - this.form.sales[0].change_price;
            this.form.sales[0].price_sale_total = numeral(this.form.sales[0].price_sale_total).subtract(diff).value()
            this.form.sales[0].price_sale_wage = numeral(this.form.sales[0].price_sale_wage).subtract(diff).value()
            this.form.sales[0].tag_wage = numeral(this.form.sales[0].price_sale_wage).divide(this.form.sales[0].qty).value()
        },
        checkout() {
            this.v.form.$reset();
            this.v.form.$touch()
            this.$nextTick(() => {
                if (this.v.form.$error) return
                this.paymentDialog = true;
            })

        },
        savePayments(e) {
            this.form.payments = e
            this.update()
        },
        update() {
            axios.post(route('api.sales.store'), this.form.data())
                .then(({data}) => {
                    this.notify('บันทึกข้อมูลเรียบร้อย')
                    _.assign(this.form, data)
                    this.form.dt = new Date(data.dt + ' UTC')
                });
        },
        printGuaranteeCard() {
            axios.get(route('api.sales.print.guarantee-card', this.form.id))
                .then(({data}) => {
                    this.$print(data)
                })
        }
    }
}
</script>

<style scoped>
.type-bar {
    top: 50px;
    height: auto;
    display: flex;
    align-items: center;
    background: white;
    border-bottom: 1px solid lightblue;
}
</style>
