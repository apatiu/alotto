<template>
    <Dialog :visible="visible"
            @update:visible="$emit('update:visible',$event)"
            header="ข้อมูลใบขายฝาก"
            modal
            :closeOnEscape="false"
            :closable="false"
            class="max-w-7xl">
        <div class="p-grid p-fluid">
            <div class="p-md-6 p-pt-3">
                <div class="p-grid">
                    <div class="p-field p-sm-4">
                        <label for="">รหัส</label>
                        <InputText v-model="item.id" disabled></InputText>
                    </div>
                    <div v-show="item.prev_id" class="p-field p-sm-4">
                        <label for="">ใบเก่า</label>
                        <InputText v-model="item.prev_id" class="w-60"></InputText>
                    </div>
                    <div v-show="item.next_id" class="p-field p-sm-4">
                        <label for="">ใบใหม่</label>
                        <InputText v-model="item.next_id" class="w-60"></InputText>
                    </div>
                </div>
                    <select-customer v-model="item.customer" force-selection
                                     :errors="v$.item.customer.$errors"></select-customer>

                <div class="p-fluid">
                    <div class="p-grid">
                        <div class="p-field p-md-5">
                            <label for="">จำนวนเงิน</label>
                            <InputNumber v-model="item.price"
                                         @input="onPriceChange($event)"
                                         disabled></InputNumber>
                            <small class="p-error" v-if="v$.item.price.$errors.length">กรุณาใส่จำนวนเงิน</small>
                        </div>
                        <div class="p-field p-md-4">
                            <label for="">อัตราดอกเบี้ย</label>
                            <InputNumber v-model="item.int_rate"></InputNumber>
                        </div>
                        <div class="p-field p-md-3">
                            <label for="">ดอกเบี้ย/เดือน</label>
                            <InputNumber v-model="item.int_per_month" disabled></InputNumber>
                        </div>
                    </div>
                    <div class="p-grid">

                        <div class="p-field p-md-5">
                            <label for="">วันที่รับ</label>
                            <Calendar v-model="item.dt"></Calendar>
                        </div>
                        <div class="p-field p-md-4">
                            <label for="">ครบอายุ</label>
                            <Calendar v-model="item.dt_end"></Calendar>
                        </div>
                        <div class="p-field p-md-3">
                            <label for="">อายุสัญญา</label>
                            <InputNumber v-model="item.life" showButtons></InputNumber>
                        </div>
                    </div>

                </div>

                <div class="flex space-x-2">


                </div>
            </div>
            <div class="p-md-6 p-pt-3">
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
                    <Column :exportable="false">
                        <template #body="{index}">
                            <Button icon="pi pi-trash"
                                    class="p-button-text p-button-rounded p-button-danger p-button-sm"
                                    @click="removeIntReceive(index)"></Button>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
        <div class="p-fluid p-form-grid p-grid mt-8">
            <div class="p-col">
                <select-gold-percent v-model="pawnItem.gold_percent"/>
            </div>
            <div class="p-col">
                <select-product-type v-model="pawnItem.product_type"/>
            </div>
            <div class="p-col">
                <label for="">น้ำหนัก (ก.)</label>
                <InputNumber v-model="pawnItem.weight"
                             :minFractionDigits="1"
                             :maxFractionDigits="3"
                             @input="onDetailItemWeightChange"></InputNumber>
            </div>
            <div class="p-col">
                <label for="">ราคา</label>
                <InputNumber v-model="pawnItem.price"
                             class="w-full"/>
            </div>
            <div class="p-col" style="padding-top: 1.9rem;">
                <Button icon="pi pi-plus" class="p-button-icon p-mt" @click="addItem"></Button>
            </div>

        </div>
        <div class="w-full mt-2">
            <DataTable :value="item.items" class="p-datatable-sm">
                <Column field="gold_percent" header="% ทอง"></Column>
                <Column field="product_type" header="ประเภท"></Column>
                <Column field="weight" header="น้ำหนัก"></Column>
                <Column field="price" header="ราคา"></Column>
                <Column>
                    <template #body="slotProps">
                        <Button icon="pi pi-trash" class="p-button-rounded p-button-text"
                                @click="removeItemItems(slotProps.index)"></Button>
                    </template>
                </Column>
            </DataTable>
        </div>
        <template #footer>
            <div class="flex items-center justify-between pt-2">
                <div v-show="!creating">
                    <Button label="ต่อดอก" class="p-button-info"
                            @click="actionInt"></Button>
                    <Button label="เปลี่ยนใบ" class="p-button-warning"></Button>
                    <Button label="ไถ่ถอน" class="p-button-success" @click="actionRed"></Button>
                    <Button label="คัดออก" class="p-button-danger"></Button>
                    <Button label="พิมพ์" icon="pi pi-print"
                            class="p-button-secondary ml-6"
                            @click="print"></Button>
                </div>
                <div v-show="creating"></div>
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
                        <h1>รับดอกเบี้ย</h1>
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
                                <label class="p-fixed" style="width: 100px;">ระยะเวลา</label>
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
                                    <InputNumber v-model="action.int"  input-class="text-right"></InputNumber>
                                </div>
                            </div>
                            <div class="p-field p-grid">
                                <div class="p-col-fixed" style="width: 100px;">เงินต้น</div>
                                <div class="p-col">
                                    <InputNumber v-model="item.price" disabled  input-class="text-right"></InputNumber>
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
    <div id="printable" v-html="printHtml"></div>
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

export default {
    name: "FormPawn",
    setup() {
        return {
            v$: useVuelidate()
        }
    },
    components: {InputPayment, ActionMessage, InputWeight, SelectProductType, SelectGoldPercent, SelectCustomer},
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
                price: 0
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
            }
        }
    },
    validations() {
        return {
            item: {
                customer: {required},
                price: {required},
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
        }
    },
    computed: {
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
                price: 0
            }
        },
        addItem() {
            this.item.items.push(_.assign({}, this.pawnItem));
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

            this.v$.$touch();
            if (this.v$.$error) return;
            console.log(this.item);

            _.assign(this.form, this.item);
            console.log(this.form);
            if (!this.item.id) {
                this.form.post(route('pawns.store'), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (res) => {
                        console.log(res);
                        this.load(res.props.new_id)
                    }
                })
            } else {
                this.form.put(route('pawns.update', this.form.id), {
                    preserveState: true,
                    preserveScroll: true,
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
                    console.log(res);
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
