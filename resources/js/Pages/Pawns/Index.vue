<template>
    <DataTable :value="items">
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="text-lg">รายการขายฝาก</h1>
                <Button @click="create">รับขายฝาก</Button>
            </div>
        </template>
        <Column field="id" header="รหัส"></Column>
    </DataTable>
    <form-pawn v-model:pawn-id="pawnId" v-model:visible="showForm"></form-pawn>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import FormPawn from "./FormPawn";
import SelectCustomer from "@/A/SelectCustomer";

export default {
    metaInfo: {title: 'Customers'},
    components: {SelectCustomer, FormPawn},
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
        }
    },
}
</script>
