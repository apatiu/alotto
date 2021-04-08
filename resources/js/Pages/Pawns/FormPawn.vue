<template>
    <Dialog :visible="visible"
            @update:visible="$emit('update:visible',$event)"
            :header="creating ? 'รับขายฝาก' : 'ข้อมูลใบขายฝาก ' + item.code"
            modal
            :closeOnEscape="false"
            :closable="false"
            class="max-w-6xl">
        <div class="p-grid p-fluid">
            <div class="p-md-7 p-pt-3">
                <div class="flex mb-4">
                    <div class="w-40">
                        <Button icon="pi pi-chevron-left" v-show="item.prev_id"
                                :label="item.prev_code"
                                class="p-button-outlined p-button-secondary w-full"
                                @click="load(item.prev_id)"></Button>

                    </div>
                    <div class="flex-1">
                        <Button class="p-button-primary w-full" :label="item.code"></Button>
                    </div>
                    <div class="w-40 ">
                        <Button icon="pi pi-chevron-right" v-show="item.next_id"
                                :label="item.next_code"
                                class="p-button-outlined p-button-secondary w-full"
                                @click="load(item.next_id)"></Button>
                    </div>
                </div>

                <select-customer v-model="item.customer"
                                 force-selection
                                 :errors="v$.item.customer.$errors"
                                 :disabled="!creating"
                                 :show-label="false"
                                 ></select-customer>
                <div class="p-grid mt-4">
                    <div class="p-col-6">
                        <div class="p-field p-grid">

                            <label for="" class="p-col-fixed w-20">วันที่รับ</label>
                            <Calendar v-model="item.dt" class="p-col"
                                      :disabled="!actionable  && !creating"></Calendar>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-20">ครบอายุ</label>
                            <Calendar v-model="item.dt_end"
                                      :disabled="!actionable  && !creating"
                                      class="p-col"></Calendar>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">อายุสัญญา</label>
                            <InputNumber v-model="item.life"
                                         showButtons
                                         :disabled="!actionable  && !creating"
                                         class="p-col"></InputNumber>
                        </div>
                    </div>
                    <div class="p-col-6">
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">จำนวนเงิน</label>
                            <div class="p-col">
                                <InputNumber v-model="item.price"
                                             @input="onPriceChange($event)"
                                             class="text-right"
                                             disabled></InputNumber>
                                <small class="p-error" v-if="v$.item.price.$errors.length">กรุณาใส่จำนวนเงิน</small>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">อัตราดอกเบี้ย</label>
                            <InputNumber v-model="item.int_rate"
                                         :disabled="!actionable && !creating"
                                         class="p-col"></InputNumber>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">ดอกเบี้ย/เดือน</label>
                            <InputNumber v-model="item.int_per_month" disabled
                                         class="p-col"></InputNumber>
                        </div>
                    </div>
                </div>
                <!--        add item-->
                <div class="p-fluid p-grid mt-6" v-if="creating">
                    <div class="p-col-2">
                        <select-gold-percent v-model="pawnItem.gold_percent"/>
                    </div>
                    <div class="p-col-4">
                        <select-product-type
                            v-model="pawnItem.product_type"
                            :errors="v$.pawnItem.$errors"/>
                    </div>
                    <div class="p-col-2">
                        <label for="">น้ำหนัก (ก.)</label>
                        <InputNumber v-model="pawnItem.weight"
                                     :minFractionDigits="1"
                                     :maxFractionDigits="3"
                                     inputClass="text-right"
                                     @input="onDetailItemWeightChange"></InputNumber>
                    </div>
                    <div class="p-col-2">
                        <label for="">ราคา</label>
                        <InputNumber v-model="pawnItem.price"
                                     inputClass="text-right"/>
                    </div>
                    <div class="p-col-2 p-d-flex p-jc-between pt-8">
                        <Button icon="pi pi-plus"
                                class="p-button-rounded p-ml-1"
                                @click="addItem">
                        </Button>
                    </div>

                </div>

                <!--        items table-->
                <div class="w-full mt-2">
                    <DataTable :value="item.items" class="p-datatable-sm">
                        <Column field="gold_percent" header="% ทอง"></Column>
                        <Column field="product_type" header="ประเภท"></Column>
                        <Column field="weight" header="น้ำหนัก"></Column>
                        <Column field="price" header="ราคา" class="text-right">
                            <template #body="props">
                                {{ $filters.decimal(props.data.price) }}
                            </template>
                        </Column>
                        <Column field="img" header="ภาพ" class="text-center justify-center">
                            <template #body="props">
                                <input-image v-model="props.data.img"/>
                            </template>
                        </Column>
                        <Column :exportable="false" style="flex:0 0 3rem;" class="w-20">
                            <template #body="slotProps">
                                <Button
                                    icon="pi pi-trash" class="p-button-rounded p-button-text"
                                    @click="removeItemItems(slotProps.index)"
                                    v-if="(item.items.length > 0 ) && (actionable || creating)"></Button>
                            </template>
                        </Column>
                    </DataTable>

                </div>

            </div>
            <div class="p-md-5 p-pt-3" v-if="!creating">
                <label for="">รายการดอกเบี้ย</label>
                <DataTable :value="item.int_receives"
                           :scrollable="true"
                           scrollHeight="380px"
                           class="p-datatable-sm">
                    <Column field="dt_end" header="ต่อถึงวันที่">
                        <template #body="props">
                            {{ $filters.date(props.data.dt_end) }}
                        </template>
                    </Column>
                    <Column field="dt" header="วันที่จ่าย">
                        <template #body="props">
                            {{ $filters.date(props.data.dt) }}
                        </template>
                    </Column>
                    <Column field="amount" header="จำนวนเงิน"></Column>
                    <Column field="month_pay" header="เดือน"></Column>
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
                        <Button label="ต่อดอก" class="p-button-info"
                                @click="actionInt"></Button>
                        <Button label="เปลี่ยนใบ" class="p-button-warning"
                                @click="actionChg"></Button>
                        <Button label="ไถ่ถอน" class="p-button-success" @click="actionRed"></Button>
                        <Button label="คัดออก"
                                class="p-button-danger ml-10"
                                @click="actionCut"
                                v-if="item.status === 'new' || item.status ==='int'"></Button>
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
                    <Button label="ยกเลิก" class="p-button-text" @click="$emit('update:visible',false)"></Button>
                    <Button label="บันทึก" icon="pi pi-check" @click="save"></Button>
                </div>
            </div>
        </template>
    </Dialog>

    <!--    begin action dialog -->
    <Dialog v-model:visible="actioning"
            modal :show-header="false"
            :closeOnEscape="false"
            :closable="false" class="max-w-5xl w-full">
        <div class="p-grid p-pt-4">
            <div class="p-md-7 ">
                <div v-if="action.type==='int'">
                    <h1 class="text-xl">รับดอกเบี้ย</h1>
                    <div class="p-fluid">
                        <div class="p-field p-grid">
                            <label for="" class="p-col-12 p-mb-2 p-md-3 p-mb-md-0">จำนวนเดือน</label>
                            <div class="p-col-12 p-md-3">
                                <InputNumber v-model="action.months" showButtons/>
                            </div>
                        </div>

                        <div class="p-field p-grid">
                            <label for="" class="p-col-12 p-mb-2 p-md-3 p-mb-md-0">สำหรับช่วงเวลา</label>
                            <div class="p-col-12 p-md-4">
                                <Calendar v-model="action.dt_start" disabled/>
                            </div>
                            <div class="p-col-12 p-md-4">
                                <Calendar v-model="action.dt_end" disabled/>
                            </div>
                        </div>

                    </div>
                </div>
                <div v-if="action.type==='red'">
                    <h1 class="text-2xl">ไถ่ถอน</h1>
                    <div class="p-fluid p-mt-4">
                        <div class="p-field p-grid">
                            <label class="p-col-fixed" style="width: 100px;">ระยะเวลา</label>
                            <div class="p-col">
                                <InputNumber suffix=" เดือน" v-model="action.months" disabled
                                             class="text-right"></InputNumber>
                            </div>
                            <div class="p-col">
                                <InputNumber suffix=" วัน" v-model="action.days" disabled
                                             class="text-right"></InputNumber>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <div class="p-col-fixed" style="width: 100px;">ดอกเบี้ย</div>
                            <div class="p-col">
                                <InputNumber v-model="action.int"
                                             input-class="text-right"
                                             :min="0"
                                             @input="action.int=$event.value"></InputNumber>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <div class="p-col-fixed" style="width: 100px;">เงินต้น</div>
                            <div class="p-col">
                                <InputNumber v-model="item.price" disabled input-class="text-right"></InputNumber>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="action.type==='chg'">
                    <h1 class="text-2xl">เปลี่ยนใบ</h1>
                    <div class="p-fluid p-mt-4">
                        <div class="p-field p-grid">
                            <label class="p-col-fixed" style="width: 100px;">ระยะเวลา</label>
                            <div class="p-col">
                                <InputNumber suffix=" เดือน" v-model="action.months" disabled
                                             class="text-right"></InputNumber>
                            </div>
                            <div class="p-col">
                                <InputNumber suffix=" วัน" v-model="action.days" disabled
                                             class="text-right"></InputNumber>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <div class="p-col-fixed" style="width: 100px;">ดอกเบี้ย</div>
                            <div class="p-col">
                                <InputNumber v-model="action.int"
                                             input-class="text-right"
                                             :min="0"
                                             @input="action.int=$event.value"></InputNumber>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <div class="p-col-fixed" style="width: 100px;">เงินต้น</div>
                            <div class="p-col">
                                <InputNumber v-model="item.price" disabled input-class="text-right"></InputNumber>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <div class="p-col-4">
                                <label for="">ประเภท</label>
                                <Dropdown v-model="action.isMore"
                                          :options="[{
                                                value:true, name: 'เพิ่มเงิน'},{
                                                value:false, name: 'ลดต้น' }]"
                                          optionValue="value"
                                          optionLabel="name"
                                ></Dropdown>
                            </div>
                            <div class="p-col-4">
                                <label for="">จำนวนเงิน</label>
                                <InputNumber v-model="action.amountChange" input-class="text-right"></InputNumber>
                            </div>
                            <div class="p-col-4">
                                <label for="">เงินต้นใหม่</label>
                                <InputNumber v-model="action.newPrice" disabled input-class="text-right"></InputNumber>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-md-5">
                <div class="p-field p-grid mt-10">
                    <label for="" class="p-col-12 p-mb-2 p-md-4 p-mb-md-0">วันที่ทำรายการ</label>
                    <div class="p-col-12 p-md-8">
                        <Calendar v-model="action.dt"/>
                    </div>
                </div>
                <div class="p-field p-grid">
                    <label for="" class="p-col-12 p-mb-2 p-md-4 p-mb-md-0">จำนวนเงิน</label>
                    <div class="p-col-12 p-md-8">
                        <div class="p-inputgroup">
                            <InputNumber v-model="action.amount" mode="decimal"/>
                            <span class="p-inputgroup-addon">บาท</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <input-payment v-model:visible="paymentDialog"
                       :target="action.amount"
                       @done="saveAction($event)"></input-payment>

        <template #footer>

            <Button class="p-button-text" @click="actioning=false">ยกเลิก</Button>
            <Button @click="getPayments">บันทึก</Button>
        </template>
        <!--        end action dialog-->
    </Dialog>
    <div id="printable" v-html="printHtml" class="p-d-none p-d-print-block"></div>


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
import PawnStatus from "@/A/PawnStatus";
import CaptureImage from "@/A/CaptureImage";
import InputImage from "@/A/InputImage";

export default {
    name: "FormPawn",
    setup() {
        return {
            v$: useVuelidate()
        }
    },
    components: {
        InputImage,
        PawnStatus,
        InputPayment, ActionMessage,
        InputWeight, SelectProductType,
        SelectGoldPercent, SelectCustomer
    },
    props: ['visible', 'pawnId'],
    data() {
        return {
            paymentDialog: false,
            saved: false,
            printHtml: null,
            form: this.$inertia.form({
                    id: null,
                    dt: new Date(),
                    dt_end: new Date(),
                    customer_id: 0,
                    customer: {},
                    price: 0,
                    status: null,
                    life: 0,
                    int_rate: 0,
                    items: [],
                    int_receives: []
                }
            ),
            item: {},
            customer: {
                name: '',
                phone: null,
                addr: null,
                tax_id: null
            },
            pawnItem: {
                gold_percent: '96',
                product_type: null,
                weight: 0,
                price: 0,
            },
            config: {},
            creating: true,
            actioning: false,
            actionActive: 0,
            action: {
                dt: new Date(),
                type: 'int',
                months: 0,
                amount: 0,
                dt_end: null,
                payments: []
            },
            editingItemIndex: 0,
        }
    },
    validations() {
        return {
            item: {
                customer: {required},
                price: {required},
            },
            pawnItem: {
                product_type: {required},
            }
        }
    },
    mounted() {
        axios.get('/api/pawn-configs')
            .then(response => {
                this.config = response.data
            })
    },
    watch: {
        visible(val) {
            if (val) {
                if (this.pawnId) {
                    this.creating = false;
                    this.load(this.pawnId);
                } else {
                    this.creating = true;
                    this.item = {
                        life: this.config.pawn_life,
                        int_rate: this.config.pawn_int_default_rate,
                        dt: moment().toDate(),
                        dt_end: moment().add(this.config.pawn_life, 'months').toDate(),
                        items: []
                    }
                    this.clearDetailItem();
                }
            } else {
                this.$inertia.reload();
            }
        },
        'item.int_rate': function (val) {
            this.item.int_per_month = (this.item.price * this.item.int_rate) / 100;
        },
        'action.months': function (val) {
            if (this.action.type === 'int') {
                this.action.amount = ((this.item.price * this.item.int_rate) / 100) * val;
                this.action.dt_end = moment(this.action.dt_start).add(val, 'months').toDate();
            }
        },
        'action.int': function (val) {
            if (this.action.type === 'red') {
                this.action.amount = numeral(this.item.price).add(val ?? 0).value();
            } else if (this.action.type === 'chg') {
                this.updateChgAmount()
            }
        },
        'action.amountChange': function (val) {
            if (this.action.type === 'chg') {
                this.updateChgAmount()
            }
        },
        'action.isMore': function (val) {
            if (this.action.type === 'chg') {
                this.updateChgAmount()
            }
        }
    },
    computed: {
        actionable() {
            return (this.item.status === 'new') || (this.item.status === 'int')
        },
        intPerMonth() {
            return (this.item.price * this.item.int_rate) / 100;
        }
    },
    methods: {
        load(id) {
            this.item = {}
            this.creating = false;
            axios.get(route('api.pawns.show', id))
                .then(response => {
                    console.log(response.data)
                    this.item = this.transformItem(response.data)
                })
        },
        transformItem(data) {
            data.dt = moment(data.dt).toDate()
            data.dt_end = moment(data.dt_end).toDate()
            return data;
        },
        pawnmax(w) {
            let max = (this.config.goldprice - 100) * .96; //24336
            console.log(this.config.goldprice);
            max -= (max * ((this.item.life * this.item.int_rate) / 100)) //2190.24  22145.76
            console.log(max)
            max = max * .0656 * w; //22081.98
            max = Math.floor(max / 100) * 100;
            return max
        },
        onDetailItemWeightChange(e) {
            console.log(e.value);
            this.pawnItem.price = this.pawnmax(e.value)
        },
        clearDetailItem() {
            this.pawnItem = {
                gold_percent: '96',
                product_type: null,
                weight: 0,
                price: 0,
            }
            this.v$.pawnItem.$reset();
        },
        addItem() {
            this.v$.pawnItem.$touch();
            if (this.v$.pawnItem.$error) return
            let item = _.assign({}, this.pawnItem)
            this.item.items.push(item);
            this.clearDetailItem();
            this.calcPrice();
        },
        removeItemItems(i) {
            this.item.items.splice(i, 1)
            this.calcPrice();
        },
        calcPrice() {
            let price = 0;
            _.each(this.item.items, (o) => {
                price += o.price;
            })
            this.item.price = price;
            this.onPriceChange({value: price});
        },
        onPriceChange(e) {
            this.item.int_per_month = e.value * this.item.int_rate / 100;
        },
        save() {
            if (this.item.customer) {
                this.item.customer_id = this.item.customer.id;
            }

            this.v$.item.$touch();
            if (this.v$.item.$error) return;
            _.assign(this.form, this.item);
            if (!this.item.id) {
                axios.post(route('api.pawns.store'), this.item)
                    .then(res => {
                            console.log(res);
                            this.load(res.data.id);
                        }
                    )
            } else {
                axios.put(route('api.pawns.update', this.form.id), this.item)
                    .then(res => {
                        this.notify('บันทึกข้อมูลแล้ว');
                        this.load(res.data.id);
                    })
            }
        },
        actionInt() {
            this.action.type = 'int';
            this.action.months = 1;

            let dt_end = this.item.dt;
            if (this.item.int_receives.length) {
                dt_end = this.item.int_receives[this.item.int_receives.length - 1].dt_end;
            }
            this.action.dt = moment().toDate();
            this.action.dt_start = moment(dt_end).toDate()
            this.action.dt_end = moment(dt_end).add(1, 'months').toDate();
            this.action.amount = this.intPerMonth;
            this.actionActive = 0;
            this.payments = [];
            this.actioning = true;
        },
        actionRed() {
            console.log('action red')
            axios.get(route('api.pawns.todayInt', this.item.id))
                .then((res) => {
                    this.action.type = 'red';
                    this.action.dt = new Date();
                    this.action.months = res.data.months;
                    this.action.days = res.data.days;
                    this.action.int = res.data.int;
                    this.action.amount = numeral(res.data.int).add(this.item.price).value();
                    this.actionActive = 2;
                    this.payments = [];
                    this.actioning = true;
                })
        },
        actionChg() {
            axios.get(route('api.pawns.todayInt', this.item.id))
                .then((res) => {
                    this.action.type = 'chg';
                    this.action.dt = new Date();
                    this.action.months = res.data.months;
                    this.action.days = res.data.days;
                    this.action.int = res.data.int;
                    this.action.isMore = true;
                    this.action.amountChange = 0;
                    this.payments = [];
                    this.actioning = true;
                    this.updateChgAmount();
                })
        },
        actionCut() {
            this.$confirm.require({
                message: 'ท่านแน่ใจว่าต้องการคัดออก?',
                header: 'กรุณายืนยัน',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    this.action.type = 'cut';
                    //callback to execute when user confirms the action
                    axios.post(route('api.pawns.storeAction', this.item.id), this.action)
                        .then(response => {
                            this.actioning = false;
                            this.$toast.add({
                                severity: 'success',
                                summary: 'สำเร็จ',
                                detail: 'บันทึกรายการแล้ว',
                                life: 3000
                            })
                            this.item = this.transformItem(response.data)
                        });
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        updateChgAmount() {
            if (this.action.isMore) {
                this.action.newPrice = numeral(this.item.price).add(this.action.amountChange).value()
                this.action.amount = numeral(this.action.amountChange).subtract(this.action.int).value();
            } else {
                this.action.newPrice = numeral(this.item.price).subtract(this.action.amountChange).value()
                this.action.amount = numeral(this.action.amountChange).add(this.action.int).value();
            }
        },
        getPayments() {
            this.action.payments = [];
            this.paymentDialog = true;
        },
        saveAction(event) {
            this.action.payments = event;
            axios.post(route('api.pawns.storeAction', this.item.id), this.action)
                .then(response => {
                    this.actioning = false;
                    this.$toast.add({severity: 'success', summary: 'สำเร็จ', detail: 'บันทึกรายการแล้ว', life: 3000})
                    this.item = this.transformItem(response.data)
                });
        },
        removeIntReceive(index) {
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    //callback to execute when user confirms the action
                    axios.delete(route('api.pawn-int-receives.destroy', this.item.int_receives[index].id))
                        .then(res => {
                            this.$toast.add({
                                severity: 'success',
                                summary: 'สำเร็จ',
                                detail: 'บันทึกรายการแล้ว',
                                life: 3000
                            })
                            this.item.int_receives.splice(index, 1);
                            this.load(this.item.id);
                        })
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });


        },

        print() {
            axios.get(route('api.pawns.print', this.item.id))
                .then(response => {
                    this.printHtml = response.data;
                    this.$nextTick(function () {
                        this.$htmlToPaper('printable', {
                            styles: [
                                '../css/app.css' // <- inject here
                            ]
                        });
                    })

                })
        }
    }
}
</script>

<style scoped>

</style>
