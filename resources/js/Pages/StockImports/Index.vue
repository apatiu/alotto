<template>
    <AppLayout>
        <div class="sm:p-4">
            <h1 class="mb-8 font-bold text-3xl">รับสินค้าเข้าสต๊อก</h1>
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


            </div>
            <ButtonCreate @click="$inertia.get(route('stock-imports.create'))" class="mb-1"/>
            <DataTable :value="items" dataKey="id" :rowHover="true" @row-click="rowClick">
                <Column field="code" header="#"></Column>
                <Column field="dt" header="วันที่"></Column>
                <Column field="team.name" header="สาขา"></Column>
                <Column field="cost_gold_total" header="ต้นทุนทอง"></Column>
                <Column field="cost_wage_total" header="ต้นทุนค่าแรง"></Column>
                <Column field="status" header="สถานะ"></Column>
                <template #empty>
                    <div class="p-14">ยังไม่มีข้อมูล</div>
                </template>
            </DataTable>

        </div>
    </AppLayout>
</template>

<script>
import Icon from '@/Shared/Icon'
import mapValues from 'lodash/mapValues'
import AppLayout from "@/Layouts/AppLayout";
import DialogModal from "@/Jetstream/DialogModal";

import JetActionMessage from "@/Jetstream/ActionMessage";
import Create from "@/Pages/StockImports/Create";
import AButton from "@/A/AButton";
import ALink from "@/A/ALink";
import ButtonCreate from "@/A/ButtonCreate";

export default {
    metaInfo: {title: 'รับสินค้าเข้าสต้อก'},
    components: {
        ButtonCreate,
        ALink,
        AButton,
        Create,

        JetActionMessage,
        DialogModal,
        Icon,
    },
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
        rowClick(e) {
            console.log(e)
            this.$inertia.visit(route('stock-imports.edit', e.data.id));
        }

    },
}
</script>
