<template>
    <div class="p-4">
        <h1 class="text-2xl">ประเภทสินค้า</h1>
        <Toolbar class="mb-1">
            <template #left>
                <Button label="New" icon="pi pi-plus" class="p-button-success p-mr-2" @click="onCreate"/>
            </template>
        </Toolbar>
        <DataTable ref="dt" :value="rows" v-model:selection="selectedRows" dataKey="id"
                   :paginator="true" :rows="10" :filters="filters"
                   paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                   :rowsPerPageOptions="[5,10,25]"
                   currentPageReportTemplate="Showing {first} to {last} of {totalRecords} rows">

            <Column field="name" header="Name" :sortable="true" style="min-width:16rem"></Column>
            <Column field="id" header="Code" :sortable="true" style="min-width:12rem"></Column>
            <Column :exportable="false">
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2"
                            @click="editRow(slotProps.data)"/>
                    <Button icon="pi pi-trash" class="p-button-rounded p-button-warning"
                            @click="confirmDeleteRow(slotProps.data)"/>
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="rowDialog" :style="{width: '450px'}" header="ข้อมูลขนาดสินค้า" :modal="true">
            <div class="p-fluid flex space-x-2">
                <div class="p-field">
                    <label for="name">Name</label>
                    <InputText id="name" v-model.trim="form.name" required="true" autofocus
                               :class="{'p-invalid': submitted && !form.name}"/>
                    <small class="p-error" v-if="form.errors.name">{{ form.errors.name }}</small>
                </div>
                <div class="p-field">
                    <label for="code">รหัส</label>
                    <InputText id="id" v-model.trim="form.id" required="true" autofocus
                               :class="{'p-invalid': submitted && !form.id}"/>
                    <small class="p-error" v-if="form.errors.id">{{ form.errors.id }}</small>
                </div>
            </div>
            <template #footer>
                <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                <Button label="Save" icon="pi pi-check" class="p-button-text" @click="store"/>
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteRowDialog" :style="{width: '450px'}" header="Confirm" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem"/>
                <span v-if="form.id">Are you sure you want to delete <b>{{ form.name }}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteRowDialog = false"/>
                <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteRow"/>
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteRowsDialog" :style="{width: '450px'}" header="Confirm" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem"/>
                <span v-if="form.name">Are you sure you want to delete the selected rows?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteRowsDialog = false"/>
                <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedRows"/>
            </template>
        </Dialog>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import mapValues from 'lodash/mapValues'
import AppLayout from "@/Layouts/AppLayout";
import DialogModal from "@/Jetstream/DialogModal";

import JetActionMessage from "@/Jetstream/ActionMessage";
import AButton from "@/A/AButton";

export default {
    metaInfo: {title: 'Customers'},
    components: {
        AButton,

        JetActionMessage,
        DialogModal,
        Icon,
    },
    layout: AppLayout,
    props: ['items'],
    data() {
        return {
            rows: this.items,
            rowDialog: false,
            deleteRowDialog: false,
            deleteRowsDialog: false,
            selectedRows: null,
            filters: {},
            submitted: false,
            creating: false,
            form: this.$inertia.form({
                id: null,
                name: null
            })
        }
    },
    methods: {
        formatCurrency(value) {
            if (value)
                return value.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
            return;
        },
        onCreate() {
            this.form.reset();
            this.submitted = false;
            this.rowDialog = true;
            this.creating = true;
            console.log(this.row);
        },
        hideDialog() {
            this.rowDialog = false;
            this.submitted = false;
        },
        store() {
            this.submitted = true;

            if (!this.creating) {
                this.form.put(route('product-sizes.update', this.form.id), {
                    errorBag: 'typeBag',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$toast.add({
                            severity: 'success',
                            summary: 'สำเร็จแล้ว',
                            detail: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            life: 3000
                        });
                        this.rowDialog = false;
                        this.rows = this.items;
                        this.form.reset()
                    }
                });
            } else {
                this.form.post(route('product-sizes.store'), {
                    errorBag: 'productSizeBag',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$toast.add({
                            severity: 'success',
                            summary: 'สำเร็จแล้ว',
                            detail: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            life: 3000
                        });
                        this.rowDialog = false;
                        this.rows = this.items;
                        this.form.reset()
                    }
                });
            }
        },
        editRow(row) {
            this.form.id = row.id
            this.form.name = row.name
            this.creating = false;
            this.rowDialog = true;
        },
        confirmDeleteRow(row) {
            this.form.id = row.id
            this.form.name = row.name
            this.deleteRowDialog = true;
        },
        deleteRow() {
            this.form.delete(route('product-types.destroy', this.form.id), {
                onSuccess: () => {
                    this.$toast.add({severity: 'success', summary: 'Successful', detail: 'Row Deleted', life: 3000});
                    this.rows = this.rows.filter(val => val.id !== this.form.id);
                    this.deleteRowDialog = false;
                    this.form.reset();
                }
            })
        },
        findIndexById(id) {
            let index = -1;
            for (let i = 0; i < this.rows.length; i++) {
                if (this.rows[i].id === id) {
                    index = i;
                    break;
                }
            }

            return index;
        },
        exportCSV() {
            this.$refs.dt.exportCSV();
        },
        confirmDeleteSelected() {
            this.deleteRowsDialog = true;
        },
        deleteSelectedRows() {
            this.rows = this.rows.filter(val => !this.selectedRows.includes(val));
            this.deleteRowsDialog = false;
            this.selectedRows = null;
            this.$toast.add({severity: 'success', summary: 'Successful', detail: 'Rows Deleted', life: 3000});
        }
    },
}
</script>
