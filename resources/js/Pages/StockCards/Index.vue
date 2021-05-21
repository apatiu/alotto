<template>
    <div class="flex justify-between">
        <div class="flex items-center">
            <h1 class="text-xl">ความเคลื่อนไหวสินค้า</h1>
        </div>
    </div>
    <div class="flex-col" style="height: 100vh;">
        <DataTable :value="rows.data"
                   :lazy="true"
                   :paginator="true"
                   :rows="pagination.rowsPerPage"
                   :rowHover="true"
                   ref="datatable"
                   :totalRecords="rows.total"
                   :loading="loading"
                   @page="onPage($event)"
                   v-model:selection="selectedItems"
                   dataKey="id"
                   :scrollable="true"
                   scrollHeight="flex"
                   responsiveLayout="scroll"
                   class="p-datatable-sm">
            <template #empty> ยังไม่มีข้อมูล</template>
            <template #loading>
                Loading data. Please wait.
            </template>

            <Column field="team.id" header="สาขา" class="justify-center" style="min-width: 80px;">
                <template #body="props">
                    {{ props.data.team.id }}
                </template>
            </Column>
            <Column field="dt" header="วันที่" class="justify-center" style="min-width: 80px;">
                <template #body="props">
                    {{ $filters.datetime(props.data.dt) }}
                </template>
            </Column>
            <Column field="product.code" header="รหัสสินค้า" style="min-width: 80px;"></Column>
            <Column field="product.name" header="สินค้า" style="min-width: 120px;"></Column>

            <Column field="qty_begin" header="จำนวนเริ่มต้น" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.qty_begin) }}
                </template>
            </Column>
            <Column field="qty_in" header="จำนวนเข้า" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.qty_in) }}
                </template>
            </Column>
            <Column field="qty_out" header="จำนวนออก" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.qty_out) }}
                </template>
            </Column>
            <Column field="qty_end" header="จำนวนเหลือ" class="justify-end" headerClass="text-right"
                    bodyClass="text-right text-red-600">
                <template #body="props">
                    {{ $filters.decimal(props.data.qty_end) }}
                </template>
            </Column>
            <Column field="weight_begin"
                    header="นน.เริ่มต้น" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.weight_begin,2) }}
                </template>
            </Column>
            <Column field="wt_in" header="นน.เข้า" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.wt_in,2) }}
                </template>
            </Column>
            <Column field="wt_out" header="นน.ออก" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.wt_out,2) }}
                </template>
            </Column>
            <Column field="wt_end" header="นน.เหลือ" class="justify-end"
                    headerClass="text-right"
                    bodyClass="text-right  text-yellow-600">
                <template #body="props">
                    {{ $filters.decimal(props.data.wt_end,2) }}
                </template>
            </Column>
            <Column field="ref_id" header="อ้างอิง" class="justify-center text-center">
                <template #body="props">
                    <cell-ref :data="props.data"></cell-ref>
                </template>
            </Column>

        </DataTable>
    </div>
    <form-pawn v-model:pawn-id="pawnId" v-model:visible="showForm"></form-pawn>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import SelectCustomer from "@/A/SelectCustomer";
import PawnStatus from "@/A/PawnStatus";
import SelectPawnStatus from "@/A/SelectPawnStatus";
import CellRef from "@/A/CellRef";
import FormPawn from "@/Pages/Pawns/FormPawn";

export default {
    metaInfo: {title: 'Customers'},
    components: {FormPawn, CellRef, SelectPawnStatus, PawnStatus, SelectCustomer},
    layout: AppLayout,
    props: ['filters', 'pagination', 'rows'],
    data() {
        return {
            pawnId: 0,
            showForm: false,
            selectedItems: null,
            filter: this.filters,
            loading: false
        }
    },
    methods: {
        create() {
            this.pawnId = 0
            this.showForm = true
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
