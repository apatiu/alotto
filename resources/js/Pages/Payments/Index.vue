<template>
    <app-layout>

        <Toolbar>
            <template #left>
                <Button label="เพิ่ม" icon="pi pi-plus" class="p-button-success p-mr-2" @click="openNew"/>
            </template>

        </Toolbar>

        <DataTable :value="payments"
                   dataKey="id"
                   class="p-datatable-sm">
            <Column field="team.name" header="สาขา"></Column>
            <Column field="dt" header="วันที่">
            <Column field="code" header="#"></Column>
                <template #body="props">
                    {{ $filters.datetime(props.data.dt) }}
                </template>
            </Column>
            <Column field="payment_type.name" header="ประเภท"></Column>
            <Column field="receive" header="รับ" class="text-right"></Column>
            <Column field="pay" header="จ่าย" class="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.pay) }}
                </template>
            </Column>
            <Column field="user.name" header="พนักงาน"></Column>

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
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import SelectBank from "@/A/SelectBank";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import AppLayout from "@/Layouts/AppLayout";
import CellRef from "@/A/CellRef";

export default {

    components: {
        CellRef,
        AppLayout,
        SelectBank,
        JetActionMessage,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
    },
    props: ['payments'],
    data() {
        return {
            item: {},
            itemDialog: false,
            deleteItemDialog: false,

        }
    },
    validations: {
        item: {
            name: {required, $autoDirty: true},
            bank: {required, $autoDirty: true},
            acc_no: {required, $autoDirty: true},
            acc_name: {required, $autoDirty: true},
        }
    },
    methods: {
        openNew() {
            this.item = {bank: 'BBL'};
            this.submitted = false;
            this.itemDialog = true;
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
        deleteItem() {
            this.$inertia.delete(route('bank-accounts.destroy', this.item.id), {
                onSuccess: () => {
                    this.deleteItemDialog = false;
                    this.$toast.add({severity: 'success', summary: 'Successful', detail: 'ลบข้อมูลแล้ว', life: 3000});
                }
            })

        },
    },
}
</script>
