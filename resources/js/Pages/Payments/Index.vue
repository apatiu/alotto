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
                <Calendar v-model="form.d"
                          :manualInput="false"></Calendar>
                <Button icon="pi pi-search" class="ml-2" @click="filter"></Button>
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

        <Dialog v-model:visible="itemDialog" :style="{width: '450px'}" :modal="true"
                class="p-fluid">
            <template #header>บัญชีธนาคาร</template>
            <div class="p-field">
                <label for="bankName">ชื่อเรียก</label>
                <InputText id="bankName" v-model.trim="item.name" required="true" autofocus
                           :class="{'p-invalid': v.item.name.$error}"/>
                <jet-input-error :errors="v.item.name.$errors"/>
            </div>
            <div class="p-field">
                <label for="bank">ธนาคาร</label>
                <select-bank id="bank" v-model="item.bank" required="true"/>
                <jet-input-error :errors="v.item.bank.$errors"/>
            </div>
            <div class="p-field">
                <label for="acc_no">เลขบัญชี</label>
                <InputText id="acc_no" v-model="item.acc_no" required="true"/>
                <jet-input-error :errors="v.item.acc_no.$errors"/>
            </div>
            <div class="p-field">
                <label for="acc_name">ชื่อบัญชี</label>
                <InputText id="acc_name" v-model="item.acc_name" required="true"/>
                <jet-input-error :errors="v.item.acc_name.$errors"/>
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

export default {
    name: 'Index',
    setup() {
        return {v: UseVuelidate()}
    },
    components: {
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
    props: ['payments', 'd'],
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
            item: {},
            itemDialog: false,
            deleteItemDialog: false,
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
            }
        }
    },
    watch: {
        'formPayment.payment_type_id': function (val) {
            this.$refs.inputAmount.$el.children[0].focus()
        }
    },
    mounted() {
        this.form.d = new Date(this.d)
    },
    methods: {
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
        }
    },
}
</script>
