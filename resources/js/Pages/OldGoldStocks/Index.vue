<template>
    <div class="flex justify-between">
        <div class="flex items-center">
            <h1 class="text-xl">ความเคลื่อนไหวทองเก่า</h1>
        </div>
    </div>
    <div class="flex-col" style="height: 100vh;">
        <DataTable :value="rows.data"
                   :lazy="true"
                   :paginator="true"
                   :rows="pagination.rowsPerPage"
                   ref="datatable"
                   :totalRecords="rows.total"
                   :loading="loading"
                   @page="onPage($event)"
                   v-model:selection="selectedItems"
                   dataKey="id"
                   :scrollable="true"
                   scrollHeight="flex"
                   responsiveLayout="scroll">
            <template #empty> ยังไม่มีข้อมูล</template>
            <template #loading>
                Loading data. Please wait.
            </template>
            <Column field="team.name" header="สาขา"></Column>
            <Column field="dt" header="วันที่" class="justify-center">
                <template #body="props">
                    {{ $filters.date(props.data.dt) }}
                </template>
            </Column>

            <Column field="gold_percent_id" header="จำนวนเริ่มต้น" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ props.data.gold_percent.name }}
                </template>
            </Column>
            <Column field="qty_begin" header="จำนวนเริ่มต้น" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.qty_begin,2) }}
                </template>
            </Column>
            <Column field="qty_in" header="จำนวนเข้า" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.qty_in,2) }}
                </template>
            </Column>
            <Column field="qty_out" header="จำนวนออก" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.qty_out,2) }}
                </template>
            </Column>
            <Column field="qty_remain" header="จำนวนเหลือ" class="justify-end" headerClass="text-right"
                    bodyClass="text-right">
                <template #body="props">
                    {{ $filters.decimal(props.data.qty_remain,2) }}
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
            console.log(event);
            this.$inertia.reload({
                data: {
                    page: numeral(event.page).add(1).value()
                }
            })
        },
    },
}
</script>
