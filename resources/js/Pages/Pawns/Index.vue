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
    <form-pawn v-model:record="pawn" v-model:visible="showForm"></form-pawn>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import FormPawn from "./FormPawn";

export default {
    metaInfo: {title: 'Customers'},
    components: {FormPawn},
    layout: AppLayout,
    props: {
        items: Array,
    },
    data() {
        return {
            pawn: {
                id: null,
                dt: null
            },
            showForm: false
        }
    },
    methods: {
        create() {
            this.pawn = _.mapValues(this.pawn, () => null);
            this.pawn.dt = new Date();
            this.showForm = true
        }
    },
}
</script>
