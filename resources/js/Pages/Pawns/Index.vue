<template>
    <div class="flex-col" style="height: 100vh;">
        <DataTable :value="data"
                   v-model:selection="selectedItems"
                   dataKey="id"
                   @row-click="open($event)"
                   :scrollable="true"
                   scrollHeight="flex"
                   responsiveLayout="scroll"
                   row-hover>
            <template #header>
                <div class="flex items-center justify-between">
                    <h1 class="text-lg">รายการขายฝาก</h1>
                    <Button @click="create">รับขายฝาก</Button>
                </div>
            </template>
            <template #loading>
                Loading data. Please wait.
            </template>
            <Column selectionMode="multiple"
                    headerStyle="width: 3rem; flex: initial; -webkit-box-flex: initial;"
                    bodyStyle="width: 3rem; flex: initial; -webkit-box-flex: initial;"
                    :exportable="false"></Column>
            <Column field="code" header="รหัส"
                    headerClass="w-20 flex-initial justify-center"
                    bodyClass="w-20 flex-initial justify-center"></Column>
            <Column field="customer.name" header="ลูกค้า"></Column>
            <Column field="dt" header="วันที่เริ่ม">
                <template #body="props">
                    {{ $filters.date(props.data.dt) }}
                </template>
            </Column>
            <Column field="dt_end" header="วันครบกำหนด">
                <template #body="props">
                    {{ $filters.date(props.data.dt_end) }}
                </template>
            </Column>
            <Column field="price" header="เงินต้น" headerClass="text-right" bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.price) }}
                </template>
            </Column>
            <Column field="int_rate" header="อัตราดอกเบี้ย" headerClass="text-center" bodyClass="text-center">
                <template #body="props">
                    {{ $filters.decimal(props.data.int_rate) }} %
                </template>
            </Column>
            <Column field="weight" header="น้ำหนัก" headerClass="text-right" bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.weight, 2) }} ก.
                </template>
            </Column>
            <Column field="status" header="สถานะ">
                <template #body="props">
                    <pawn-status v-model="props.data.status"></pawn-status>
                </template>
            </Column>
        </DataTable>
    </div>
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
    props: ['data'],
    data() {
        return {
            pawnId: 0,
            showForm: false,
            selectedItems: null
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
