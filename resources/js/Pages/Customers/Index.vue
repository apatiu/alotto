<template>
    <div class="sm:p-4">
        <h1 class="mb-2 font-bold text-3xl">ลูกค้า</h1>
        <div class="mb-2 flex justify-between items-center">
            <a-button @click="create" color="primary">
                <span>เพิ่ม</span>
            </a-button>
        </div>
        <DataTable :value="items" @rowClick="onRowClick"
                   autoLayout rowHover>
            <Column field="id" header="รหัส"></Column>
            <Column field="name" header="ชื่อ"></Column>
            <Column field="address" header="ที่อยู่"></Column>
            <Column field="city" header="ตำบล"></Column>
            <Column field="district" header="อำเภอ"></Column>
            <Column field="province" header="จังหวัด"></Column>
            <Column field="tax_id" header="เลขบัตรประชาชน"></Column>
        </DataTable>
        <FormCustomer v-model:visible="dlgCustomer"
                      :data="editingRow"
                      @created="onCreated"></FormCustomer>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import mapValues from 'lodash/mapValues'
import AppLayout from "@/Layouts/AppLayout";
import DialogModal from "@/Jetstream/DialogModal";

import JetActionMessage from "@/Jetstream/ActionMessage";
import Create from "@/Pages/Customers/Create";
import AButton from "@/A/AButton";
import FormCustomer from "@/Pages/Customers/FormCustomer";

export default {
    metaInfo: {title: 'Customers'},
    components: {
        FormCustomer,
        AButton,
        Create,

        JetActionMessage,
        DialogModal,
        Icon,
    },
    layout: AppLayout,
    props: {
        items: Array,
        filters: Object,
        editingItem: Object,
    },
    data() {
        return {
            formSearch: {
                search: this.filters.search,
                role: this.filters.role,
                trashed: this.filters.trashed,
            },
            dlgCustomer: false,
            editingRow: null,
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        create() {
            this.editingRow = null
            this.dlgCustomer = true
        },
        edit(id) {
            this.$inertia.visit(route('customers.edit', id));
        },
        onCreated() {
            this.$inertia.reload()
        },
        onRowClick(e) {
            this.editingRow = e.data
            this.dlgCustomer = true
        }
    },
}
</script>
