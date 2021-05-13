<template>
    <Dialog :visible="visible"
            @update:visible="$emit('update:visible',$event)"
            :header="creating ? 'รับขายฝาก' : 'ข้อมูลใบขายฝาก ' + form.code"
            modal
            :closeOnEscape="false"
            :closable="false"
            :class="classDialog">
        <div class="p-grid" ref="dlgFormPawn">
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

                            <label for="" class="p-col-fixed w-20">วันที่รับ</label>
                            <Calendar v-model="form.dt" class="p-col"
                                      :disabled="!actionable  && !creating"></Calendar>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-20">ครบอายุ</label>
                            <Calendar v-model="form.dt_end"
                                      :disabled="!actionable  && !creating"
                                      class="p-col"></Calendar>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">อายุสัญญา</label>
                            <InputNumber v-model="form.life"
                                         showButtons
                                         :disabled="!actionable  && !creating"
                                         class="p-col"></InputNumber>
                        </div>
                    </div>
                    <div class="p-col-6">
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">จำนวนเงิน</label>
                            <div class="p-col">
                                <InputNumber v-model="form.price"
                                             @input="onPriceChange($event)"
                                             class="text-right"
                                             disabled></InputNumber>
                                <small class="p-error" v-if="v.form.price.$errors.length">กรุณาใส่จำนวนเงิน</small>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">อัตราดอกเบี้ย</label>
                            <InputNumber v-model="form.int_rate"
                                         :disabled="!actionable && !creating"
                                         class="p-col"></InputNumber>
                        </div>
                        <div class="p-field p-grid">
                            <label for="" class="p-col-fixed w-28">ดอกเบี้ย/เดือน</label>
                            <InputNumber v-model="form.int_per_month" disabled
                                         class="p-col"></InputNumber>
                        </div>
                    </div>
                </div>
                <!--        add item-->
                <div class="p-fluid p-grid mt-6" v-if="creating">
                    <div class="p-col-2">
                        <select-gold-percent v-model="pawnItem.gold_percent_id"/>
                    </div>
                    <div class="p-col-4">
                        <select-product-type
                            v-model="pawnItem.product_type"
                            :errors="v.pawnItem.$errors"
                        />
                    </div>
                    <div class="p-col-2">
                        <label for="">น้ำหนัก (ก.)</label>
                        <InputNumber v-model="pawnItem.weight"
                                     :minFractionDigits="1"
                                     :maxFractionDigits="3"
                                     inputClass="text-right"
                                     @input="onDetailItemWeightChange"></InputNumber>
                    </div>
                    <div class="p-col-4 flex">
                        <div class="flex-1">
                            <label for="">ราคา</label>
                            <InputNumber v-model="pawnItem.price"
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
                        <Column field="gold_percent_id" header="% ทอง"></Column>
                        <Column field="product_type" header="ประเภท">
                            <template #body="props">
                                {{ props.data.product_type.name ?? props.data.product_type }}
                            </template>
                        </Column>
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
                                    v-if="(form.items.length > 0 ) && (actionable || creating)"></Button>
                            </template>
                        </Column>
                    </DataTable>

                </div>

            </div>
            <div class="p-md-5" v-if="!creating">
                <pawn-status v-model="form.status" class="mb-8"></pawn-status>
                <label class="font-bold text-sm">รายการดอกเบี้ย</label>
                <DataTable :value="form.int_receives"
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
                                v-if="form.status === 'new' || form.status ==='int'"></Button>
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
                    <Button label="บันทึก" icon="pi pi-check"
                            @click="save"
                            v-if="creating || actionable"></Button>
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
                                <InputNumber v-model="form.price" disabled input-class="text-right"></InputNumber>
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
                                <InputNumber v-model="form.price" disabled input-class="text-right"></InputNumber>
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
            v: useVuelidate()
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
                    dt: null,
                    dt_end: null,
                    customer_id: null,
                    customer: null,
                    price: 0,
                    status: null,
                    life: null,
                    int_rate: null,
                    items: [],
                    int_receives: []
                }
            ),
            pawnItem: {
                gold_percent_id: 96,
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
            form: {
                customer_id: {required},
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
                    this.form.reset();
                    this.form = _.assign(this.form, {
                        life: this.config.pawn_life,
                        int_rate: this.config.pawn_int_default_rate,
                        dt: new Date(),
                        dt_end: moment().add(this.config.pawn_life, 'months').toDate(),
                        items: []
                    })
                    this.clearDetailItem();
                }
            } else {
                this.$inertia.reload();
            }
        },
        'form.dt': function(val) {
            if (this.creating) {
                this.form.dt_end = moment(val).add(this.form.life,'months').toDate();
            }
        },
        'form.life': function(val) {
            if (this.creating) {
                this.form.dt_end = moment(this.form.dt).add(val,'months').toDate()
            }
        },
        'form.int_rate': function (val) {
            this.form.int_per_month = (this.form.price * this.form.int_rate) / 100;
        },
        'action.months': function (val) {
            if (this.action.type === 'int') {
                this.action.amount = ((this.form.price * this.form.int_rate) / 100) * val;
                this.action.dt_end = moment(this.action.dt_start).add(val, 'months').toDate();
            }
        },
        'action.int': function (val) {
            if (this.action.type === 'red') {
                this.action.amount = numeral(this.form.price).add(val ?? 0).value();
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
            return (this.form.status === 'new') || (this.form.status === 'int')
        },
        intPerMonth() {
            return (this.form.price * this.form.int_rate) / 100;
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
            console.log('Loading Pawn data...')
            let loader = this.$loading.show({
                container: this.$refs.dlgFormPawn
            })
            this.form.reset();
            this.creating = false;
            axios.get(route('api.pawns.show', id))
                .then(response => {
                    this.form = _.assign(this.form, this.transformItem(response.data))
                })
                .catch((e) => {
                    this.notify('เกิดข้อผิดพลาด');
                })
                .finally((e) => {
                    loader.hide()
                })
        },
        transformItem(data) {
            data.dt = new Date(data.dt + ' UTC')
            data.dt_end = new Date(data.dt_end + ' UTC')
            data.dt_out = new Date(data.dt_out + ' UTC')
            return data;
        },
        pawnmax(w) {
            let max = (this.config.goldprice - 100) * .96; //24336
            max -= (max * ((this.form.life * this.form.int_rate) / 100)) //2190.24  22145.76
            max = max * .0656 * w; //22081.98
            max = Math.floor(max / 100) * 100;
            return max
        },
        onDetailItemWeightChange(e) {
            this.pawnItem.price = this.pawnmax(e.value)
        },
        clearDetailItem() {
            this.pawnItem = {
                gold_percent_id: 96,
                product_type: null,
                weight: 0,
                price: 0,
            }
            this.v.pawnItem.$reset();
        },
        addItem() {
            this.v.pawnItem.$touch();
            if (this.v.pawnItem.$error) return
            let item = _.assign({}, this.pawnItem)
            this.form.items.push(item);
            this.clearDetailItem();
            this.calcPrice();
        },
        removeItemItems(i) {
            this.form.items.splice(i, 1)
            this.calcPrice();
        },
        calcPrice() {
            let price = 0;
            _.each(this.form.items, (o) => {
                price += o.price;
            })
            this.form.price = price;
            this.onPriceChange({value: price});
        },
        onPriceChange(e) {
            this.form.int_per_month = e.value * this.form.int_rate / 100;
        },
        save() {

            this.v.form.$touch();
            if (this.v.form.$error) {
                console.log('v.form.$error')
                return
            }

            if (!this.form.id) {
                console.log(this.form.data())
                axios.post(route('api.pawns.store'), this.form.data())
                    .then(res => {
                            this.notify('บันทึกข้อมูลแล้ว');
                            this.load(res.data.id);
                        }
                    )
            } else {
                axios.put(route('api.pawns.update', this.form.id), this.form.data())
                    .then(res => {
                        this.notify('บันทึกข้อมูลแล้ว');
                        this.load(res.data.id);
                    })
            }
        },
        actionInt() {
            this.action.type = 'int';
            this.action.months = 1;

            let dt_end = this.form.dt;
            if (this.form.int_receives.length) {
                dt_end = this.form.int_receives[this.form.int_receives.length - 1].dt_end;
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
            axios.get(route('api.pawns.todayInt', this.form.id))
                .then((res) => {
                    this.action.type = 'red';
                    this.action.dt = new Date();
                    this.action.months = res.data.months;
                    this.action.days = res.data.days;
                    this.action.int = res.data.int;
                    this.action.amount = numeral(res.data.int).add(this.form.price).value();
                    this.actionActive = 2;
                    this.payments = [];
                    this.actioning = true;
                })
        },
        actionChg() {
            axios.get(route('api.pawns.todayInt', this.form.id))
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
                    axios.post(route('api.pawns.storeAction', this.form.id), this.action)
                        .then(response => {
                            this.actioning = false;
                            this.$toast.add({
                                severity: 'success',
                                summary: 'สำเร็จ',
                                detail: 'บันทึกรายการแล้ว',
                                life: 3000
                            })
                            this.form = _.assign(this.form, this.transformItem(response.data))
                        });
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });
        },
        updateChgAmount() {
            if (this.action.isMore) {
                this.action.newPrice = numeral(this.form.price).add(this.action.amountChange).value()
                this.action.amount = numeral(this.action.amountChange).subtract(this.action.int).value();
            } else {
                this.action.newPrice = numeral(this.form.price).subtract(this.action.amountChange).value()
                this.action.amount = numeral(this.action.amountChange).add(this.action.int).value();
            }
        },
        getPayments() {
            this.action.payments = [];
            this.paymentDialog = true;
        },
        saveAction(event) {
            this.action.payments = event;
            axios.post(route('api.pawns.storeAction', this.form.id), this.action)
                .then(response => {
                    this.actioning = false;
                    this.$toast.add({severity: 'success', summary: 'สำเร็จ', detail: 'บันทึกรายการแล้ว', life: 3000})
                    this.form = _.assign(this.form, this.transformItem(response.data))
                });
        },
        removeIntReceive(index) {
            this.$confirm.require({
                message: 'Are you sure you want to proceed?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    //callback to execute when user confirms the action
                    axios.delete(route('api.pawn-int-receives.destroy', this.form.int_receives[index].id))
                        .then(res => {
                            this.$toast.add({
                                severity: 'success',
                                summary: 'สำเร็จ',
                                detail: 'บันทึกรายการแล้ว',
                                life: 3000
                            })
                            this.form.int_receives.splice(index, 1);
                            this.load(this.form.id);
                        })
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });


        },

        print() {
            axios.get(route('api.pawns.print', this.form.id))
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
