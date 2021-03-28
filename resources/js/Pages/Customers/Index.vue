<template>
    <div class="sm:p-4">
        <h1 class="mb-8 font-bold text-3xl">ลูกค้า</h1>
        <div class="mb-6 flex justify-between items-center">
            <!--            <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">-->
            <!--                <label class="block text-gray-700">Role:</label>-->
            <!--                <select v-model="form.role" class="mt-1 w-full form-select">-->
            <!--                    <option :value="null" />-->
            <!--                    <option value="user">Manager</option>-->
            <!--                    <option value="owner">Officer</option>-->
            <!--                </select>-->
            <!--                <label class="mt-4 block text-gray-700">Trashed:</label>-->
            <!--                <select v-model="form.trashed" class="mt-1 w-full form-select">-->
            <!--                    <option :value="null" />-->
            <!--                    <option value="with">With Trashed</option>-->
            <!--                    <option value="only">Only Trashed</option>-->
            <!--                </select>-->
            <!--            </search-filter>-->
            <a-button @click="create" color="primary">
                <span>เพิ่ม</span>
            </a-button>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <DataTable :value="items">
                <Column field="id" header="รหัส"></Column>
                <Column field="name" header="ชื่อ"></Column>
                <Column field="address" header="ที่อยู่"></Column>
                <Column field="city" header="ตำบล"></Column>
                <Column field="district" header="อำเภอ"></Column>
                <Column field="province" header="จังหวัด"></Column>
                <Column field="tax_id" header="เลขบัตรประชาชน"></Column>
            </DataTable>
        </div>
        <create :show="showCreateModal" @close="showCreateModal=false"></create>
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

    export default {
        metaInfo: {title: 'Customers'},
        components: {
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
                showCreateModal: false,
                editId: null,
            }
        },
        methods: {
            reset() {
                this.form = mapValues(this.form, () => null)
            },
            create() {
                this.showCreateModal = true
            },
            edit(id) {
                this.$inertia.visit(route('customers.edit', id));
            }

        },
    }
</script>
