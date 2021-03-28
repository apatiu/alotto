<template>

    <div class="sm:p-4">
        <h1 class="mb-8 font-bold text-3xl">สินค้า</h1>

        <DataTable :value="items">
            <Column field="team_name" header="สาขา"></Column>
            <Column field="product_id" header="รหัส"></Column>
            <Column field="name" header="ชื่อสินค้า"></Column>
            <Column field="weight_gram" header="นน./ชิ้น"></Column>
            <Column field="cost_wage" header="ค่าแรงทุน"></Column>
            <Column field="tag_wage" header="ค่าแรงขาย"></Column>
            <Column field="qty" header="จำนวน"></Column>
        </DataTable>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import mapValues from 'lodash/mapValues'
import AppLayout from "@/Layouts/AppLayout";
import DialogModal from "@/Jetstream/DialogModal";

import JetActionMessage from "@/Jetstream/ActionMessage";
import Create from "@/Pages/Customers/Create";


export default {
    metaInfo: {title: 'สินค้า'},
    components: {
        AppLayout,
        Create,

        JetActionMessage,
        DialogModal,
        Icon,
    },
    layout: AppLayout,
    props: {
        items: Array,
        filters: Object,
    },
    data() {
        return {
            formSearch: {
                search: this.filters.search,
                role: this.filters.role,
                trashed: this.filters.trashed,
            },
            showProductImport: false,
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        edit(id) {
            this.$inertia.visit(route('products.edit', id));
        }

    },
}
</script>
