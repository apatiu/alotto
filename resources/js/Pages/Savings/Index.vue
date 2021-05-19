<template>
    <div class="flex justify-between">
        <div class="flex items-center">
            <h1 class="text-xl">รายการออมทอง</h1>
        </div>
        <div class="p-formgroup-inline justify-end items-center">
            <div class="p-field">
                <InputText ref="inputSearch"
                           v-model="filter.q"
                           placeholder="ค้นหาจากชื่อ, หมายเลข"
                           @input="onSearch"></InputText>
            </div>
            <div class="p-field">
                <select-saving-status v-model="filter.status" @update:modelValue="onFilter"/>
            </div>
            <div class="p-field">
                <div class="p-inputgroup">
                    <InputNumber v-model="filter.dt_due_over" class="w-24"
                                 placeholder="เกินกำหนด"
                                 @update:modelValue="onFilter"></InputNumber>
                    <div class="p-inputgroup-addon">วัน</div>
                </div>

            </div>
            <div class="p-field mr-0">
                <Button icon="pi pi-times" class="p-button-text" @click="clearFilters"></Button>
            </div>
            <Divider layout="vertical"/>
            <div class="p-field mr-0">
                <Button @click="create" icon="pi pi-circle-plus" label="เปิดออม" class="p-button-success"></Button>
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
                   :loading="isLoading"
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
            <Column field="dt_due" header="วันครบกำหนด" class="justify-center">
                <template #body="props">
                    {{ $filters.date(props.data.dt_due) }}
                </template>
            </Column>
            <Column field="dt_close" header="วันปิดออม" class="justify-center">
                <template #body="props">
                    {{ $filters.date(props.data.dt_close) }}
                </template>
            </Column>
            <Column field="price_pay" header="ยอดออม" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.price_pay) }}
                </template>
            </Column>

            <Column field="status" header="สถานะ" class="justify-center">
                <template #body="props">
                    <saving-status v-model="props.data.status"></saving-status>
                </template>
            </Column>
        </DataTable>
    </div>
    <form-saving v-model:saving-id="savingId" v-model:visible="showForm"></form-saving>

</template>


<script>
import AppLayout from "@/Layouts/AppLayout";
import FormSaving from "./FormSaving";
import SelectCustomer from "@/A/SelectCustomer";
import SavingStatus from "@/A/SavingStatus";
import SelectSavingStatus from "@/A/SelectSavingStatus";

export default {
    inheritAttrs: false,
    metaInfo: {title: 'Customers'},
    components: {SavingStatus, SelectCustomer, FormSaving, SelectSavingStatus},
    layout: AppLayout,
    props: ['filters', 'pagination', 'data'],
    data() {
        return {
            isLoading: false,
            savingId: null,
            showForm: false,
            selectedItems: null,
            filter: this.filters,
        }
    },
    watch: {
        filter: {
            handler() {

            },
            deep: true
        },
        showForm(val) {
            if (!val) {
                this.$nextTick(() => {
                    this.onFilter()
                })
            }
        }
    },
    mounted() {
        this.$refs.inputSearch.$el.focus();
    },
    methods: {
        create() {
            this.savingId = null
            this.showForm = true
        },
        open(e) {
            this.savingId = e.data.id;
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
        clearFilters() {
            this.filter = {
                status: 'open',
                dt_due_over: null,
            };
            this.onFilter()
        }
    },
}
</script>
