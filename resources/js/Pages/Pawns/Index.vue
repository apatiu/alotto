<template>
    <div class="flex justify-between">
        <div class="flex items-center">
            <h1 class="text-xl">รายการขายฝาก</h1>
        </div>
        <div class="p-formgroup-inline justify-end items-center">
            <div class="p-field">
                <InputText ref="inputSearch"
                           v-model="filter.q"
                           placeholder="ค้นหาจากชื่อ, หมายเลข"
                           @input="onSearch"></InputText>
            </div>
            <div class="p-field">
                <select-pawn-status v-model="filter.status" @update:modelValue="onFilter"/>
            </div>
            <div class="p-field mr-0">
                <div class="p-inputgroup">
                    <InputNumber v-model="filter.dt_end_over" class="w-24"
                                 placeholder="เกินกำหนด"
                                 @update:modelValue="onFilter"></InputNumber>
                    <div class="p-inputgroup-addon">วัน</div>
                </div>

            </div>
            <Divider layout="vertical"/>
            <div class="p-field mr-0">
                <Button @click="create" icon="pi pi-circle-plus" label="รับขายฝาก" class="p-button-success"></Button>
            </div>
        </div>
    </div>
    <div class="flex-col" style="height: 100vh;">
        <DataTable :value="data.data"
                   :lazy="true"
                   :paginator="true"
                   :rows="pagination.rowsPerPage"
                   ref="datatable"
                   :totalRecords="data.total"
                   :loading="loading"
                   @page="onPage($event)"
                   v-model:selection="selectedItems"
                   dataKey="id"
                   @row-click="open($event)"
                   :scrollable="true"
                   scrollHeight="flex"
                   responsiveLayout="scroll"
                   row-hover>
            <template #empty> ยังไม่มีข้อมูล</template>
            <template #loading>
                Loading data. Please wait.
            </template>
            <Column selectionMode="multiple"
                    headerStyle="width: 3rem; flex: initial; -webkit-box-flex: initial;"
                    bodyStyle="width: 3rem; flex: initial; -webkit-box-flex: initial;"
                    frozen
                    :exportable="false"></Column>
            <Column field="team.name" header="สาขา"></Column>
            <Column field="code" header="รหัส"
                    headerClass="w-20 flex-initial justify-center"
                    bodyClass="w-20 flex-initial justify-center"></Column>
            <Column field="customer.name" header="ลูกค้า"
                    class="min-w-200"></Column>
            <Column field="dt" header="วันที่เริ่ม" class="justify-center">
                <template #body="props">
                    {{ $filters.date(props.data.dt) }}
                </template>
            </Column>
            <Column field="dt_end" header="วันครบกำหนด" class="justify-center">
                <template #body="props">
                    {{ $filters.date(props.data.dt_end) }}
                </template>
            </Column>
            <Column field="price" header="เงินต้น" class="justify-end" headerClass="text-right" bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.price) }}
                </template>
            </Column>
            <Column field="int_rate" header="อัตราดอกเบี้ย" class="justify-end" headerClass="text-center"
                    bodyClass="text-center">
                <template #body="props">
                    {{ $filters.decimal(props.data.int_rate) }} %
                </template>
            </Column>
            <Column field="weight" header="น้ำหนัก" class="justify-end" headerClass="text-right" bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.weight, 2) }} ก.
                </template>
            </Column>
            <Column field="status" header="สถานะ" class="justify-center">
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
import SelectPawnStatus from "@/A/SelectPawnStatus";

export default {
    metaInfo: {title: 'Customers'},
    components: {SelectPawnStatus, PawnStatus, SelectCustomer, FormPawn},
    layout: AppLayout,
    props: ['filters', 'pagination', 'data'],
    data() {
        return {
            pawnId: 0,
            showForm: false,
            selectedItems: null,
            filter: this.filters,
            loading: false
        }
    },
    watch: {
        filter: {
            handler() {

            },
            deep: true
        }
    },
    mounted() {
        this.$refs.inputSearch.$el.focus();
    },
    methods: {
        create() {
            this.pawnId = 0
            this.showForm = true
        },
        open(e) {
            this.pawnId = e.data.id;
            this.showForm = true;
        },
        onSearch(e) {
            let q = e.target.value
            clearTimeout(timeout);
            let timeout = setTimeout(() => {
                this.filter.q = q;
                this.onFilter()
            }, 300)
        },
        onFilter() {
            this.$inertia.reload({
                data: {filters: this.filter}
            });
        },
        onPage(event) {
            this.$inertia.reload({
                data: {
                    page: numeral(event.page).add(1).value()
                }
            })
        },
    },
}
</script>
