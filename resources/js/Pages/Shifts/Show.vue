<template>
    <app-layout>
        <Toolbar>
            <template #left>
                <h4>ข้อมูลกะทำงาน</h4>
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

        <DataTable :value="shift.payments"
                   dataKey="id"
                   class="p-datatable-sm"
                   rowHover autoLayout>
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
                        <div class="p-field p-grid">
                            <label class="p-col-fixed w-24">ส่งเซฟ</label>
                            <div class="p-col">
                                <InputBaht v-model="formShift.cash_to_safe" disabled></InputBaht>
                            </div>
                        </div>
                        <div class="p-field p-grid" >
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

        <h5 v-if="shiftClosed">ปิดกะทำงานไปเมื่อ {{ $filters.datetime(formShift.closed_at) }}</h5>

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
            formShift: this.$inertia.form(this.shift),
            item: {},
            itemDialog: false,
            deleteItemDialog: false,
            closing: false,
        }
    },
    validations() {
        return {
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
