<template>
    <DataTable :value="items" @row-click="open($event)"
               row-hover>
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-lg">รายการขายฝาก</h1>
                <Button @click="create">รับขายฝาก</Button>
            </div>
        </template>
        <Column field="id" header="รหัส"></Column>
        <Column field="customer.name" header="ลูกค้า"></Column>
        <Column field="dt" header="วันที่เริ่ม">
            <template #body="props">
                {{ $filters.date( props.data.dt )}}
            </template>
        </Column>
        <Column field="dt_end" header="วันครบกำหนด">
            <template #body="props">
                {{ $filters.date( props.data.dt_end )}}
            </template>
        </Column>
        <Column field="price" header="เงินต้น"></Column>
        <Column field="status" header="สถานะ">
            <template #body="props">
                <pawn-status v-model="props.data.status"></pawn-status>
            </template>
        </Column>
    </DataTable>
    <form-pawn v-model:pawn-id="pawnId" v-model:visible="showForm"></form-pawn>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import FormPawn from "./FormPawn";
import SelectCustomer from "@/A/SelectCustomer";
import PawnStatus from "@/A/PawnStatus";

export default {
    metaInfo: {title: 'Customers'},
    components: {PawnStatus, SelectCustomer, FormPawn},
    layout: AppLayout,
    props: {
        items: Array,
    },
    data() {
        return {
            pawnId: 0,
            showForm: false,
        }
    },
    methods: {
        create() {
            this.pawnId = 0
            this.showForm = true
        },
        open(e) {
            this.pawnId = e.data.id;
            this.showForm = true;
        }
    },
}
</script>
