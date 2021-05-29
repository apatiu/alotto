<template>
    <app-layout>
        <Toolbar>
            <template #left>
                <div class="flex space-x-2 ">
                    <div>สร้างรายการเงินสด:</div>
                    <select-payment-type
                        v-model="formPayment.payment_type"
                        :refable="false"
                        @update:modelValue="formPayment.payment_type_id = $event.id"
                    ></select-payment-type>
                    <InputNumber v-model="formPayment.amount"
                                 ref="inputAmount"
                                 inputClass="text-right"></InputNumber>
                    <Button label="เพิ่มรายการ" @click="storePayment"
                            :disabled="v.formPayment.$invalid"></Button>
                </div>
            </template>
            <template #right>
                <div class="flex space-x-2 items-center">
                    <label>กะทำงาน</label>
                    <Calendar v-model="form.d"
                              :manualInput="false"></Calendar>
                    <Button icon="pi pi-search" class="ml-2" @click="filter"></Button>
                </div>
            </template>
        </Toolbar>

        <DataTable :value="payments"
                   dataKey="id"
                   class="p-datatable-sm"
                   rowHover autoLayout>
            <Column field="team.name" header="สาขา"></Column>
            <Column field="dt" header="วันที่">
                <Column field="code" header="#"></Column>
                <template #body="props">
                    {{ $filters.datetime(props.data.dt, false) }}
                </template>
            </Column>
            <Column field="payment_type.name" header="ประเภท"></Column>
            <Column field="method.name" header="ช่องทาง"></Column>
            <Column field="receive" header="รับ" class="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.receive) }}
                </template>
            </Column>
            <Column field="pay" header="จ่าย" class="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.pay) }}
                </template>
            </Column>
            <Column field="user.name" header="พนักงาน"></Column>
            <Column>
                <template #body="props">
                    <ButtonTrash @click="removePayment(props)"
                                 v-if="!props.data.paymentable_id"/>
                </template>
            </Column>

        </DataTable>

        <Card class="shift-summary mt-4">
            <template #title>
                สรุปกะทำงาน
            </template>
            <template #content>
                <div class="flex space-x-4 p-fluid">
                    <div class="sum-cash">
                        <h5>เงินสด</h5>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">เริ่มต้น</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.cash_begin" disabled></InputBaht>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">รับ</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.cash_in" disabled></InputBaht>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">จ่าย</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.cash_out" disabled></InputBaht>
                            </div>
                        </div>
                        <div class="p-field p-grid" v-if="closing">
                            <label class="p-col-fixed w-24">ส่งเซฟ</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.cash_to_safe" disabled></InputBaht>
                            </div>
                        </div>
                        <div class="p-field p-grid" v-if="closing">
                            <label class="p-col-fixed w-24">ส่งธนาคาร</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.cash_to_bank" disabled></InputBaht>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">คงเหลือ</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.cash_end" disabled></InputBaht>
                            </div>
                        </div>
                    </div>
                    <Divider layout="vertical"/>
                    <div class="sum-bank-transfer">
                        <h5>โอน</h5>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">โอนเข้า</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.bank_transfer_in" disabled></InputBaht>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">โอนออก</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.bank_transfer_out" disabled></InputBaht>
                            </div>
                        </div>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">รวม</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.bank_transfer_end" disabled></InputBaht>
                            </div>
                        </div>
                    </div>
                    <Divider layout="vertical"/>
                    <div class="sum_pawn">
                        <h5>ขายฝาก</h5>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">ยอดเงิน</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.pawn_amount" disabled></InputBaht>
                            </div>
                        </div>
                        <Divider/>

                        <h5>ออมทอง</h5>
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">ยอดเงิน</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.saving_amount" disabled></InputBaht>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Card>

        <Expanable v-if="!shiftClosed" triggerClass="block text-center" class="my-8 ">
            <template #trigger>
                <Button label="ปิดกะทำงาน"
                        class="p-button-outlined p-button-danger w-80"
                        @click="closing=true"
                        v-if="!closing"
                ></Button>
            </template>
            <template #content>
                <Card class="bg-yellow-50">
                    <template #content>
                        <div class="p-fluid">
                            <div class="flex">
                                <div>
                                    <h5>เงินสดปิดกะ</h5>
                                    <div class="p-field p-grid">
                                        <label class="p-col-fixed w-24">ส่งเซฟ</label>
                                        <div class="p-col">
                                            <InputBaht v-model="formShift.cash_to_safe"></InputBaht>
                                        </div>
                                    </div>
                                    <div class="p-field p-grid">
                                        <label class="p-col-fixed w-24">ส่งธนาคาร</label>
                                        <div class="p-col">
                                            <InputBaht v-model="formShift.cash_to_bank"></InputBaht>
                                        </div>
                                    </div>
                                    <div class="p-field p-grid">
                                        <label class="p-col-fixed w-24">คงเหลือ</label>
                                        <div class="p-col">
                                            <InputBaht v-model="formShift.cash_end" disabled></InputBaht>
                                        </div>
                                    </div>

                                    <div class="p-field p-grid">
                                        <label class="p-col-fixed w-24">ยอดยกไป</label>
                                        <div class="p-col">
                                            <InputBaht v-model="formShift.cash_count"/>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <Button label="ตัวช่วยนับ" class="w-auto p-button-info"></Button>
                                    </div>
                                </div>
                                <Divider layout="vertical"></Divider>
                                <div class="sum-old-gold">
                                    <h5>ทองเก่า</h5>
                                    <div class="flex space-x-2">
                                        <div>
                                            <h6>ตามบิล</h6>
                                            <div class="p-field p-grid">
                                                <label class="p-col-fixed w-24">96.5</label>
                                                <div class="p-col">
                                                    <InputGram v-model="formShift.old_gold_96"
                                                               class="w-24" disabled></InputGram>
                                                </div>
                                            </div>
                                            <div class="p-field p-grid">
                                                <label class="p-col-fixed w-24">90.0</label>
                                                <div class="p-col">
                                                    <InputGram v-model="formShift.old_gold_90" class="w-24"
                                                               disabled></InputGram>
                                                </div>
                                            </div>
                                            <div class="p-field p-grid">
                                                <label class="p-col-fixed w-24">99.99</label>
                                                <div class="p-col">
                                                    <InputGram v-model="formShift.old_gold_99" class="w-24"
                                                               disabled></InputGram>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <h6>ชั่งจริง</h6>
                                            <div class="p-field p-grid">
                                                <div class="p-col">
                                                    <InputGram v-model="formShift.real_old_gold_96"
                                                               class="w-24"></InputGram>
                                                </div>
                                            </div>
                                            <div class="p-field p-grid">
                                                <div class="p-col">
                                                    <InputGram v-model="formShift.real_old_gold_90"
                                                               class="w-24"></InputGram>
                                                </div>
                                            </div>
                                            <div class="p-field p-grid">
                                                <div class="p-col">
                                                    <InputGram v-model="formShift.real_old_gold_99"
                                                               class="w-24"></InputGram>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <Divider layout="vertical"/>
                                <div class="p-field">
                                    <label>นับสต้อก</label>
                                    <Button label="นับสต้อก"/>
                                </div>
                            </div>

                        </div>
                        <div class="text-center">
                            <Button label="เริ่มกระบวนการปิดกะทำงาน"
                                    class="w-96 p-button-danger my-8"
                                    v-if="!shiftClosed"
                                    @click="closeShift"/>
                        </div>
                    </template>
                </Card>
            </template>
        </Expanable>
        <h5 v-if="shiftClosed">ปิดกะทำงานไปเมื่อ {{ formShift.closed_at }}</h5>


        <Dialog v-model:visible="itemDialog" :style="{width: '450px'}" :modal="true"
                class="p-fluid">
            <template #header>บัญชีธนาคาร</template>
            <div class="p-field">
                <label for="bankName">ชื่อเรียก</label>
                <InputText id="bankName" v-model.trim="item.name" required="true" autofocus
                           :class="{'p-invalid': v.item.name.$error}"/>
                <FormInputError :errors="v.item.name.$errors"/>
            </div>
            <div class="p-field">
                <label for="bank">ธนาคาร</label>
                <select-bank id="bank" v-model="item.bank" required="true"/>
                <FormInputError :errors="v.item.bank.$errors"/>
            </div>
            <div class="p-field">
                <label for="acc_no">เลขบัญชี</label>
                <InputText id="acc_no" v-model="item.acc_no" required="true"/>
                <FormInputError :errors="v.item.acc_no.$errors"/>
            </div>
            <div class="p-field">
                <label for="acc_name">ชื่อบัญชี</label>
                <InputText id="acc_name" v-model="item.acc_name" required="true"/>
                <FormInputError :errors="v.item.acc_name.$errors"/>
            </div>
            <template #footer>
                <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveItem"/>
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteItemDialog" :style="{width: '450px'}" header="Confirm" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem"/>
                <span v-if="item">Are you sure you want to delete <b>{{ item.name }}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteItemDialog = false"/>
                <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteItem"/>
            </template>
        </Dialog>
    </app-layout>

</template>

<script>

import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import ALabel from "@/A/ALabel"
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import SelectBank from "@/A/SelectBank";
import {required} from "@vuelidate/validators";
import AppLayout from "@/Layouts/AppLayout";
import CellRef from "@/A/CellRef";
import SelectPaymentType from "@/A/SelectPaymentType";
import UseVuelidate from "@vuelidate/core";
import ButtonTrash from "@/A/ButtonTrash";
import InputBaht from "@/A/InputBaht";
import InputGram from "@/A/InputGram";
import JetDropdown from "@/Jetstream/JetDropdown";
import Expanable from "@/A/Expanable";

export default {
    name: 'Index',
    setup() {
        return {v: UseVuelidate()}
    },
    components: {
        Expanable,
        JetDropdown,
        InputGram,
        InputBaht,
        ButtonTrash,
        SelectPaymentType,
        CellRef,
        AppLayout,
        SelectBank,
        JetActionMessage,
        JetFormSection,
        JetInput,
        JetInputError,
        ALabel,
        JetSecondaryButton,
    },
    props: ['payments', 'd', 'shift'],
    data() {
        return {
            form: this.$inertia.form({
                d: this.d
            }),
            formPayment: this.$inertia.form({
                payment_type_id: null,
                method_id: 'cash',
                amount: null,
            }),
            formShift: this.$inertia.form(this.shift),
            item: {},
            itemDialog: false,
            deleteItemDialog: false,
            closing: false,
        }
    },
    validations() {
        return {
            item: {
                name: {required, $autoDirty: true},
                bank: {required, $autoDirty: true},
                acc_no: {required, $autoDirty: true},
                acc_name: {required, $autoDirty: true},
            },
            formPayment: {
                payment_type_id: {required},
                amount: {required}
            },
            formShift: {}
        }
    },
    watch: {
        'formPayment.payment_type_id': function (val) {
            this.$refs.inputAmount.$el.children[0].focus()
        },
        'formShift.cash_to_safe': function (val) {
            this.updateCashEnd()
        },
        'formShift.cash_to_bank': function (val) {
            this.updateCashEnd()
        },
        'formShift.recentlySuccessful': function (val) {
            if (val)
                this.notify('ปิดกะทำงานสำเร็จแล้ว')
        }
    },
    mounted() {
        this.form.d = new Date(this.d)
    },
    computed: {
        shiftClosed() {
            return this.formShift.status === 'close'
        }
    },
    methods: {
        updateCashEnd() {
            this.formShift.cash_end = numeral(this.formShift.cash_begin)
                .add(this.formShift.cash_in)
                .subtract(this.formShift.cash_out)
                .subtract(this.formShift.cash_to_safe)
                .subtract(this.formShift.cash_to_bank)
                .value();
        },
        storePayment() {

            this.formPayment.post(route('payments.store'), {
                onSuccess: (e) => {
                    this.formPayment.reset();
                }
            })
        },
        filter() {
            this.form.get(route('payments.index'))
        },
        hideDialog() {
            this.itemDialog = false;
            this.submitted = false;
        },
        saveItem() {
            this.submitted = true;

            this.v.$touch()
            if (this.v.$error) return

            if (this.item.name.trim()) {
                if (this.item.id) {
                    this.form.reset();
                    _.assign(this.form, this.item);
                    this.form.put(route('bank-accounts.update', this.item.id), {
                        onSuccess: () => {
                            this.$toast.add({
                                severity: 'success',
                                summary: 'Successful',
                                detail: 'Item Updated',
                                life: 3000
                            });
                        }
                    })

                } else {
                    this.form.reset();
                    _.assign(this.form, this.item);
                    this.form.post(route('bank-accounts.store'), {
                        onSuccess: () => {
                            this.$toast.add({
                                severity: 'success',
                                summary: 'Successful',
                                detail: 'Item Created',
                                life: 3000
                            });
                        }
                    });
                }

                this.itemDialog = false;
                this.item = {};
            }
        },
        editItem(item) {
            this.item = {...item};
            this.itemDialog = true;
        },
        confirmDeleteItem(item) {
            this.item = item;
            this.deleteItemDialog = true;
        },
        removePayment(e) {
            console.log(e);
        },
        closeShift() {
            this.formShift.status = 'close'
            this.formShift.closed_at = new Date();
            this.formShift.put(route('shifts.update', this.shift.id));
        }
    },
}
</script>
