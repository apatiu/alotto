<template>
    <div class="p-flex border-b">
        <div class="cell-gold-price">{{  goldprice }}</div>
    </div>
    <div class="p-grid p-mt-2">
        <div class="p-col-8">
            <div class="p-flex-column">
                <div class="cell-sell">
                    <div class="card">
                        <div class="p-flex-column">
                            <h5>ขาย</h5>
                            <div>
                                <InputText></InputText>
                                <Button label="S"></Button>
                            </div>
                            <DataTable
                                :value="form.sales"
                                class="p-datatable-sm p-mt-1"
                                :scrollable="true"
                                scrollHeight="400px"
                                scrollDirection="both">
                                <Column field="product_id" header="รหัส" frozen class="w-40"></Column>
                                <Column field="product_name" header="ชื่อสินค้า" class="w-40"></Column>
                                <Column field="qty" header="จำนวน" class="w-20">
                                    <template #footer>
                                        0.00
                                    </template>
                                </Column>
                                <Column field="product_wt" header="นน." class="w-20"></Column>
                                <Column field="wt" header="นน.รวม" class="w-20"></Column>
                                <Column field="price_sale_gold" header="ราคาทอง" class="w-20"></Column>
                                <Column field="price_sale_wage" header="ค่าแรง" class="w-20"></Column>
                                <Column field="discount" header="ส่วนลด" class="w-20"></Column>
                                <Column field="deposit" header="มัดจำ" class="w-20"></Column>
                                <Column field="price_sale_total" header="รวม" class="w-20">
                                    <template #footer>
                                        0.00
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
                        <div class="p-grid">
                            <div class="p-col">
                                <select-gold-percent></select-gold-percent>
                            </div>
                            <div class="p-col">
                                <select-product-type></select-product-type>
                            </div>
                            <div class="p-col">
                                <input-weight></input-weight>
                            </div>
                            <div class="p-col">
                                <label for="">ราคารรับซื้อ</label>
                                <InputNumber input-class="w-full"/>
                            </div>
                        </div>
                        <DataTable :value="form.buys" class="p-mt-1">
                            <Column field="product_percent" header="%" frozen footer=""></Column>
                            <Column field="product_type" header="สินค้า" footer=""></Column>
                            <Column field="wt" header="นน.รวม"></Column>
                            <Column field="price_buy_calc" header="ราคาคำนวณ"></Column>
                            <Column field="price_buy_total" header="ราคารับซื้อ">
                                <template #footer>
                                    0.00
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
                    <select-customer></select-customer>
                </div>
                <div class="cell-bill bg-yellow-50 shadow p-4">
                    <p class="text-center font-bold text-sm">{{ form.total }}</p>
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

export default {
    name: "Pos",
    components: {SelectProductType, InputWeight, SelectGoldPercent},
    props: {
        bill: Object,
        goldprice: Object,
    },
    data() {
        return {
            form: this.$inertia.form({}),
            goldprice: 0,
        }
    },
    computed :{
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
                status: 'open'
            })
        },
        getGoldPrice() {
            axios.get(route('api.goldprice'))
                .then(({data}) => {
                    this.goldprice = data
                })
        },
        update() {
            this.form.post(route('sales.store'))
        }
    }
}
</script>

<style scoped>

</style>
