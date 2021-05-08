<template>

    <div class="layout-topbar type-bar">
        <SelectButton v-model="form.type"
                      :options="types"
                      optionLabel="label"
                      optionValue="value">
            <template #option="slotProps">
                <div class="type-options w-28">
                    <div>{{ slotProps.option.label }}</div>
                </div>
            </template>
        </SelectButton>
        <div class="cell-bill p-4 flex flex-1 justify-end">
            <div :class="['text-right font-bold text-5xl pr-4',classBillTotal]">{{ $filters.decimal(billTotal) }}</div>
            <Button label="รับชำระเงิน"
                    class="p-button-lg"
                    :disabled="form.type===null"
                    @click="checkout"></Button>
        </div>
    </div>
    <div class="mt-24"></div>
    <template v-if="form.type">
        <div class="p-field" v-if="form.code">
            <label for="">รหัส</label>
            <InputText v-model="form.code"/>
        </div>
        <div class="mt-2 p-grid">
            <div class="p-col-9">
                <select-customer
                    v-model="form.customer"
                    @update:modelValue="form.customer_id = $event.id"
                    class="bg-white"></select-customer>
            </div>
            <div class="p-col-3">
                <gold-prices></gold-prices>
            </div>
        </div>
        <div class="p-flex-column mt-2">
            <div class="cell-sell" v-if="form.type !== 'buy'">
                <Card>
                    <template #title>ขาย</template>
                    <template #content>
                        <div class="p-flex-column">
                            <div>
                                <select-product
                                    v-model="product"
                                    @select="onSelectProduct($event)"
                                ></select-product>
                            </div>
                            <DataTable
                                :value="form.sales"
                                class="p-datatable-sm p-mt-1"
                                editMode="cell"
                                @cellEditComplete="onSalesCellEditComplete"
                                :scrollable="true">
                                <Column field="product_code" header="รหัส" frozen class="w-40"></Column>
                                <Column field="product_name" header="ชื่อสินค้า" style="min-width:170px;"></Column>
                                <Column field="qty" header="จำนวน" class="w-20 justify-center">
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
                                <Column field="wt" header="นน.รวม" class="w-20"></Column>
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
                                <Column headerClass="text-center" bodyClass="justify-center" style="width: 3rem">
                                    <template #body="{index}">
                                        <Button type="button"
                                                icon="pi pi-trash"
                                                class="p-button-sm p-button-rounded p-button-text p-button-plain"
                                                @click="removeSale(index)"></Button>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </template>
                </Card>
            </div>
            <div class="cell-buy mt-2" v-if="form.type !== 'sale'">
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
                    <DataTable :value="form.buys" class="p-datatable-sm p-mt-1">
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
                        <Column headerClass="text-center w-20">
                            <template #body="{index}">
                                <Button type="button"
                                        icon="pi pi-trash"
                                        class="p-button-sm p-button-rounded p-button-text p-button-plain"
                                        @click="removeBuy(index)"></Button>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
            <div>
                {{ v.form.$errors }}
            </div>
        </div>


    </template>
    <input-payment v-model:visible="paymentDialog"
                   :target="form.total_amount"
                   @done="savePayments($event)"></input-payment>
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

export default {
    name: "Pos",
    setup() {
        const v = useVuelidate()
        return {v}
    },
    components: {
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
            types: [
                {label: 'ขาย', icon: 'pi pi-arrow-up', value: 'sale'},
                {label: 'ซื้อ', icon: 'pi pi-arrow-up', value: 'buy'},
                {label: 'เปลี่ยน', icon: 'pi pi-arrow-up', value: 'change'}
            ],
            form: this.$inertia.form({
                id: null,
                code: null,
                dt: new Date(),
                gold_price: this.goldprice,
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
                gold_price_buy: 0,
                sales: [],
                buys: [],
                payments: []

            }),
            product: null,
            buy:
                {
                    product_percent_id: 96,
                    product_type: '',
                    wt: 0,
                    price_buy_calc: 0,
                    price_buy_total: 0,
                },
            paymentDialog: false,
            editingSalesCellRows: {},
            editingBuysCellRows: {},
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
                },
                // buys: {
                //     minLength: minLength(this.form.type !== 'sales' ? 1 : 0)
                // }
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
        },
        classBillTotal() {
            return {
                'text-blue-600': this.form.total_amount >= 0,
                'text-red-600': this.form.total_amount < 0
            }
        }
    },
    mounted() {
        if (!this.bill) {
            this.form.gold_price = this.getGoldPrice();
            this.create()
        } else {
        }
    },
    methods: {
        create() {
            console.log('create')
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
                    this.form.gold_price = data
                })
        },
        onSelectProduct(e) {
            this.product = null;
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
            sale.product_id = e.product_id
            sale.product_code = e.code
            sale.product_type = e.product_type
            sale.product_design = e.product_design
            sale.product_size = e.product_size
            sale.product_name = e.name
            sale.gold_percent = e.gold_percent
            sale.discount = sale.deposit = 0
            return sale
        },
        getPriceSaleGold(goldPercent, wtGram, goldprice = null) {
            goldprice = (goldprice) ? goldprice : this.form.gold_price;
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
            let gPercent = await this.getGoldPercent(this.buy.product_percent_id)
            let o = numeral(this.form.gold_price)
                .subtract(gPercent.deduct_buy)
                .multiply(gPercent.percent_buy / 100);

            this.form.gold_price_buy = o.value();

            o.multiply(wt).divide(15.2);
            o.multiply(1 - (gPercent.percent_deduct_total_buy / 100));
            return o.value()
        },
        addBuy() {
            let buy = _.assign({}, this.buy, {
                status: 'buy'
            })
            buy.product_type = this.buy.product_type.name
            this.form.buys.push(buy)
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
            this.setLoading();
            this.form.post(route('sales.store'), {
                onSuccess: (e) => {
                    this.notify('บันทึกข้อมูลเรียบร้อย')
                    this.create()

                }
            });
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
