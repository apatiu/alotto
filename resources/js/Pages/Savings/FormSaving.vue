<template>
    <Dialog :visible="visible"
            @update:visible="$emit('update:visible',$event)"
            :header="creating ? 'เปิดออม' : 'ข้อมูลใบออมทอง: ' + form.code"
            modal
            :closeOnEscape="false"
            :closable="false"
            :class="classDialog">
        <div class="p-grid" ref="dlgFormSaving">
            <div :class="{'p-md-7':!creating,'p-col':creating}">
                <div class="flex space-x-1" v-if="!creating">
                    <div class="w-40" v-if="form.prev_id">
                        <Button icon="pi pi-chevron-left"
                                :label="form.prev_code"
                                class="p-button-outlined p-button-secondary w-full"
                                @click="load(form.prev_id)"></Button>

                    </div>
                    <div class="flex-1">
                        <Button class="p-button-primary w-full" :label="form.code" disabled></Button>
                    </div>
                    <div class="w-40" v-if="form.next_id">
                        <Button icon="pi pi-chevron-right" v-show="form.next_id"
                                :label="form.next_code"
                                class="p-button-outlined p-button-secondary w-full"
                                @click="load(form.next_id)"></Button>
                    </div>
                </div>
                <div class="mt-8">
                    <select-customer v-model="form.customer"
                                     @update:modelValue="($event) ? form.customer_id=$event.id ?? null : null"
                                     :errors="v.form.customer_id.$errors"
                                     :disabled="!creating"
                                     :show-label="false"
                    ></select-customer>
                </div>
                <div class="p-grid mt-4">
                    <div class="p-col-6">
                        <div class="p-field p-grid">

                            <label for="" class="p-col-fixed w-24">วันที่เร่ิม</label>
                            <Calendar v-model="form.dt" class="p-col"
                                      :disabled="!actionable  && !creating"></Calendar>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-24">ครบกำหนด</label>
                            <Calendar v-model="form.dt_due"
                                      :disabled="!actionable  && !creating"
                                      class="p-col"></Calendar>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-24">อายุสัญญา</label>
                            <div class="p-inputgroup p-col">
                                <InputNumber v-model="form.life"
                                             :disabled="!actionable  && !creating"
                                ></InputNumber>
                                <div class="p-inputgroup-addon">
                                    <span>เดือน</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-col-6">

                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">ยอดออม</label>
                            <InputNumber v-model="form.price_pay"
                                         suffix=" บาท"
                                         disabled
                                         inputClass="text-right"
                                         class="p-col"></InputNumber>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">ยอดเป้าหมาย</label>
                            <InputNumber v-model="form.price_total"
                                         suffix=" บาท"
                                         disabled
                                         inputClass="text-right"
                                         class="p-col"></InputNumber>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">ยอดเหลือ</label>
                            <InputNumber v-model="form.price_remain"
                                         suffix=" บาท"
                                         disabled
                                         inputClass="text-right"
                                         class="p-col"></InputNumber>
                        </div>
                    </div>
                </div>
                <!--        add item-->
                <div class="p-fluid p-grid mt-6">
                    <div class="p-col-2">
                        <select-product v-model="product" @select="onSelectProduct"></select-product>
                    </div>
                    <div class="p-col-4">
                        <label>ชื่อสินค้า</label>
                        <InputText v-model="newItem.product_name"
                                   :class="{'p-invalid': v.newItem.product_name.$error}"></InputText>
                        <InputError :modelValue="v.newItem.product_name.$errors"/>
                    </div>
                    <div class="p-col-2">
                        <label for="">น้ำหนัก (ก.)</label>
                        <InputNumber v-model="newItem.wt"
                                     :minFractionDigits="1"
                                     :maxFractionDigits="3"
                                     inputClass="text-right"></InputNumber>
                    </div>
                    <div class="p-col-4 flex">
                        <div class="flex-1">
                            <label for="">ราคา</label>
                            <InputNumber v-model="newItem.price"
                                         inputClass="text-right"/>
                        </div>
                        <Button icon="pi pi-plus"
                                class="p-button-rounded p-ml-1"
                                style="margin-top:23px;"
                                @click="addItem">
                        </Button>
                    </div>
                </div>

                <!--        items table-->
                <div class="w-full mt-2">
                    <DataTable :value="form.items" class="p-datatable-sm">
                        <Column field="product.code" header="รหัสสินค้า"></Column>
                        <Column field="product_name" header="ประเภท">
                            <template #body="props">
                                {{ props.data.product_name }}
                            </template>
                        </Column>
                        <Column field="wt" header="น้ำหนัก"></Column>
                        <Column field="price_total" header="ราคา" class="text-right">
                            <template #body="props">
                                {{ $filters.decimal(props.data.price_total) }}
                            </template>
                        </Column>

                        <Column :exportable="false" style="flex:0 0 3rem;" class="w-20">
                            <template #body="slotProps">
                                <Button
                                    icon="pi pi-trash" class="p-button-rounded p-button-text"
                                    @click="removeItemItems(slotProps.index)"
                                    v-if="(form.items.length > 0 ) && (actionable || creating)"></Button>
                            </template>
                        </Column>
                    </DataTable>

                </div>

            </div>
            <div class="p-md-5" v-if="!creating">
                <saving-status v-model="form.status" class="mb-8"></saving-status>
                <label class="font-bold text-sm">รายการส่งออม</label>
                <DataTable :value="form.details"
                           :scrollable="true"
                           scrollHeight="380px"
                           class="p-datatable-sm">
                    <Column field="dt" header="วันที่">
                        <template #body="props">
                            {{ $filters.date(props.data.dt) }}
                        </template>
                    </Column>
                    <Column field="amount" header="จำนวนเงิน"></Column>
                    <Column field="note" header="หมายเหตุ"></Column>
                    <Column :exportable="false" style="flex: 0 0 3rem">
                        <template #body="{index}">
                            <Button icon="pi pi-trash"
                                    class="p-button-text p-button-rounded p-button-danger p-button-sm"
                                    @click="removeIntReceive(index)"
                                    v-if="actionable"></Button>
                        </template>
                    </Column>
                </DataTable>
            </div>

        </div>
        <template #footer>
            <div class="flex items-center justify-between pt-2">
                <div class="p-d-flex">
                    <div v-show="actionable">
                        <Button label="ส่งออม" class="p-button-info"
                                @click="actionDeposit"></Button>
                        <Button label="ปิดออม" class="p-button-info"
                                @click="actionClose"></Button>
                    </div>
                    <div>
                        <Button label="พิมพ์" icon="pi pi-print"
                                class="p-button-secondary ml-6"
                                @click="print"
                                v-show="!creating"></Button>
                    </div>
                </div>
                <div class="flex items-center">
                    <action-message :on="form.recentlySuccessful">บันทึกข้อมูลแล้ว</action-message>
                    <Button label="ปิด" class="p-button-text" @click="$emit('update:visible',false)"></Button>
                    <Button label="บันทึก" icon="pi pi-check"
                            @click="save"
                            v-if="creating || actionable"></Button>
                </div>
            </div>
        </template>
    </Dialog>

    <!--    begin action dialog -->
    <Dialog v-model:visible="dlgDeposit"
            modal
            header="ส่งออม"
            :closeOnEscape="false"
            :closable="false" class="max-w-xl">

        <div class="p-field p-grid mt-4">
            <label class="p-col-fixed w-24">วันที่</label>
            <div class="p-col">
                <Calendar v-model="formDeposit.dt"/>
            </div>
        </div>
        <div class="p-field p-grid">
            <label class="pp-col-fixed w-24">จำนวนเงิน</label>
            <div class="p-col">
                <div class="p-inputgroup">
                    <InputNumber v-model="formDeposit.amount" mode="decimal" inputClass="text-right"/>
                    <span class="p-inputgroup-addon">บาท</span>
                </div>
                <InputError :model-value="v.formDeposit.amount.$errors"></InputError>
            </div>
        </div>
        <template #footer>
            <Button class="p-button-text" @click="dlgDeposit=false">ยกเลิก</Button>
            <Button @click="getPayments(formDeposit.amount)" :disabled="v.formDeposit.amount.$error">ตกลง</Button>
        </template>
        <!--        end action dialog-->
    </Dialog>
    <input-payment v-model:visible="paymentDialog"
                   :target="paymentTarget"
                   @done="saveDeposit($event)"></input-payment>
</template>

<script>
import SelectCustomer from "@/A/SelectCustomer";
import SelectGoldPercent from "@/A/SelectGoldPercent";
import SelectProductType from "@/A/SelectProductType";
import useVuelidate from '@vuelidate/core';
import {required} from '@vuelidate/validators'
import InputWeight from "@/A/InputWeight";
import ActionMessage from "@/Jetstream/ActionMessage";
import InputPayment from "@/A/InputPayment";
import SavingStatus from "@/A/SavingStatus";
import CaptureImage from "@/A/CaptureImage";
import InputImage from "@/A/InputImage";
import SelectProduct from "@/A/SelectProduct";
import InputError from "@/Jetstream/InputError";

export default {
    name: "FormSaving",
    setup() {
        return {
            v: useVuelidate()
        }
    },
    components: {
        InputError,
        SelectProduct,
        InputImage,
        SavingStatus,
        InputPayment, ActionMessage,
        InputWeight, SelectProductType,
        SelectGoldPercent, SelectCustomer
    },
    props: ['visible', 'savingId'],
    data() {
        return {
            paymentDialog: false,
            paymentTarget: null,
            saved: false,
            form: this.$inertia.form({
                id: null,
                code: null,
                customer_id: null,
                customer: {},
                dt: null,
                dt_due: null,
                dt_close: null,
                price_total: null,
                price_pay: null,
                price_remain: null,
                note: null,
                items_wt: null,
                items_qty: 0,
                status: null,
                user_id: null,
                price_refund: null,
                price_forward: null,
                prev_id: null,
                next_id: null,
                items: [],
                gold_price_sale: null
            }),
            formDeposit: this.$inertia.form({
                amount: null,
                dt: new Date(),
                note: null,
                wt: null,
                gold_price_sale: null
            }),
            newItem: {
                product_id: null,
                product_name: null,
                product_wt: null,
                qty: null,
                wt: null,
                price: null,
                price_total: null
            },
            config: {},
            creating: true,
            editingItemIndex: 0,
            payments: [],
            dlgDeposit: false,
            product: null,
        }
    },
    validations() {
        return {
            form: {
                customer_id: {required},
                dt: {required},

            },
            formDeposit: {
                amount: {required}
            },
            newItem: {
                product_name: {required},
                product_wt: {required},
                qty: {required},
                wt: {required},
                price: {required},
                price_total: {required},
            },
        }
    },
    mounted() {
        // axios.get('/api/saving-configs')
        //     .then(response => {
        //         this.config = response.data
        //     })
    },
    watch: {
        async visible(val) {
            this.goldPriceSale = await this.$a.getGoldPriceSale()
            if (val) {
                if (this.savingId) {
                    this.creating = false;
                    this.load(this.savingId);
                } else {
                    console.log('Creating saving.')
                    this.creating = true;
                    this.form.reset();
                    this.form = _.assign(this.form, {
                        life: null,
                        dt: new Date(),
                    })
                    this.form.gold_price_sale = this.goldPriceSale
                    this.resetNewItem();
                }
            }
        },
        'form.dt': function (val) {
            if (this.form.life > 0) {
                this.form.dt_due = moment(val).add(this.form.life, 'months').toDate();
            } else this.form.dt_due = null
        },
        'form.life': function (val) {
            if (val)
                this.form.dt_due = moment(this.form.dt).add(val, 'months').toDate()
        },
        'form.items': {
            handler(val) {
                this.form.price_total = _.sumBy(val, 'price_total')
            }, deep: true
        }
    },
    computed: {
        actionable() {
            return (this.form.status === 'open')
        },
        classDialog() {
            return {
                'max-w-6xl': !this.creating,
                'max-w-3xl': this.creating,
            }
        },
        classLeft() {
            return {
                'mt-2': true,
                'p-md-7 p-pt-3': !this.creating
            }
        }
    },
    methods: {
        load(id) {
            console.log('Loading Saving data...')
            this.showLoader();
            this.form.reset();
            this.formDeposit.reset();
            this.creating = false;
            this.dlgDeposit = false;
            axios.get(route('api.savings.show', id))
                .then(response => {
                    this.form = _.assign(this.form, this.transformItem(response.data))
                    setTimeout(() => {
                        this.hideLoader()
                    }, 500);
                })
                .catch((e) => {
                    this.notify('เกิดข้อผิดพลาด');
                })
                .finally((e) => {

                })
        },
        transformItem(data) {
            data.dt = new Date(data.dt + ' UTC')
            data.dt_due = new Date(data.dt_due + ' UTC')
            data.dt_close = new Date(data.dt_close + ' UTC')
            return data;
        },
        resetNewItem() {
            this.newItem = {
                product_id: null,
                product_name: null,
                product_wt: null,
                qty: null,
                wt: null,
                price: null,
                price_total: null
            }
            this.v.newItem.$reset();
        },
        async onSelectProduct(e) {
            this.newItem.product = e
            this.newItem.product_id = e.id
            this.newItem.product_name = e.name
            this.newItem.product_wt = this.newItem.wt = e.wtGram
            this.newItem.qty = 1
            let gold_price = await this.$a.getGoldPrice();
            let psp = this.$a.calcProductSalePrice(e, gold_price.gold_price_sale)
            this.newItem.price = this.newItem.price_total = psp;
        },
        addItem() {
            this.v.newItem.$touch();
            if (this.v.newItem.$error) return

            let item = _.assign({}, this.newItem)
            this.form.items.push(item);
            this.resetNewItem();
        },
        removeItemItems(i) {
            this.form.items.splice(i, 1)
        },
        save() {
            this.v.form.$touch();
            if (this.v.form.$error) {
                console.log('v.form.$error')
                return
            }

            if (!this.form.id) {
                console.log('Creating gold saving data.')
                axios.post(route('api.savings.store'), this.form.data())
                    .then(res => {
                            this.notify('บันทึกข้อมูลแล้ว');
                            this.load(res.data.id);
                        }
                    )
            } else {
                axios.put(route('api.savings.update', this.form.id), this.form.data())
                    .then(res => {
                        this.notify('บันทึกข้อมูลแล้ว');
                        this.load(res.data.id);
                    })
            }
        },
        getPayments(target = 0) {
            console.log('Get payment')
            if (target > 0) {
                this.paymentTarget = target
            }
            this.payments = [];
            this.paymentDialog = true;
        },
        actionDeposit() {
            this.formDeposit.reset();
            this.formDeposit.dt = new Date()
            this.formDeposit.amount = 0
            this.formDeposit.gold_price_sale = this.goldPriceSale
            this.dlgDeposit = true
        },
        saveDeposit(e) {
            axios.post(route('api.savings.actions.deposit', this.form.id), {
                deposit: this.formDeposit.data(),
                payments: e
            }).then(({data}) => {
                this.load(data.id)
            })
        },
        actionClose() {

        },
        print() {
            axios.get(route('api.savings.print', this.form.id))
                .then(response => {
                    this.printHtml = response.data;
                    this.$print(response.data);

                })
        }
    }
}
</script>

<style scoped>

</style>
