<template>
    <div class="sm:p-4">
        <h1 class="text-2xl">เปอร์เซ็นทอง</h1>
        <DataTable :value="rows" editMode="cell"
                   @cell-edit-complete="onCellEditComplete"
                   class="editable-cells_table"
                   responsiveLayout="scroll">
            <Column field="gold_percent" header="เปอร์เซ็นทอง" key="name">
                <template #editor="slotProps">
                    <InputText :modelValue="slotProps.data[slotProps.column.props.field]"
                               @update:modelValue="onCellEdit($event, slotProps)"/>
                </template>
            </Column>
            <Column field="id" header="รหัส">
                <template #editor="slotProps">
                    <InputText :modelValue="slotProps.data[slotProps.column.props.field]"
                               @update:modelValue="onCellEdit($event, slotProps)"/>
                </template>
            </Column>
            <Column field="percent_sale" header="% ขาย">
                <template #editor="slotProps">
                    <InputNumber :modelValue="slotProps.data[slotProps.column.props.field]"
                                 @update:modelValue="onCellEdit($event, slotProps)"
                                 suffix="%"/>
                </template>
            </Column>
            <Column field="add_sale" header="บวกเพิ่มขาย">
                <template #editor="slotProps">
                    <InputNumber :modelValue="slotProps.data[slotProps.column.props.field]"
                                 @update:modelValue="onCellEdit($event, slotProps)"/>
                </template>
            </Column>
            <Column field="percent_buy" header="% รับซื้อ">
                <template #editor="slotProps">
                    <InputNumber :modelValue="slotProps.data[slotProps.column.props.field]"
                                 @update:modelValue="onCellEdit($event, slotProps)"
                                 suffix="%"/>
                </template>
            </Column>
            <Column field="deduct_buy" header="หักเพิ่มรับซื้อ">
                <template #editor="slotProps">
                    <InputNumber :modelValue="slotProps.data[slotProps.column.props.field]"
                                 @update:modelValue="onCellEdit($event, slotProps)"/>
                </template>
            </Column>
            <Column field="percent_deduct_total_buy" header="% หักรับซื้อรวม">
                <template #editor="slotProps">
                    <InputNumber :modelValue="slotProps.data[slotProps.column.props.field]"
                                 @update:modelValue="onCellEdit($event, slotProps)"
                                 suffix="%"/>
                </template>
            </Column>

        </DataTable>
        <div class="mt-3 text-sm">
            <p> ราคาขายทองรูปพรรณ = ( ((ราคาขายทองแท่ง + บวกเพิ่มขาย) * (% ขาย/100)) * น.น.ขายทอง ) / 15.2 ) +
                ค่าแรงขาย</p>
            <p> ราคารับซื้อทองรูปพรรณ = ( ((ราคาขายทองแท่ง - หักเพิ่มรับซื้อ) * (% รับซื้อ/100)) * น.น.ขายทอง ) / 15.2 )
                - (ราคารวม - (% หักรับซื้อรวม/100))</p>
        </div>
        <!--        {{ items }}-->
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import mapValues from 'lodash/mapValues'
import AppLayout from "@/Layouts/AppLayout";
import DialogModal from "@/Jetstream/DialogModal";

import JetActionMessage from "@/Jetstream/ActionMessage";
import AButton from "@/A/AButton";

export default {
    metaInfo: {title: 'Customers'},
    components: {
        AButton,

        JetActionMessage,
        DialogModal,
        Icon,
    },
    layout: AppLayout,
    props: ['items'],
    data() {
        return {
            editingCellRows: {},
            rows: this.items,
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        create() {
            this.showCreateModal = true
        },
        edit(id) {
            this.$inertia.visit(route('suppliers.edit', id));
        },
        onCellEditComplete(event) {
            if (!this.editingCellRows[event.index]) {
                return;
            }

            const editingCellValue = this.editingCellRows[event.index][event.field];

            switch (event.field) {
                case 'id':
                    if (this.isPositiveInteger(editingCellValue))
                        this.rows[event.index] = {...this.editingCellRows[event.index]};
                    else
                        event.preventDefault();
                    break;

                case 'percent_sale':
                case 'add_sale':
                case 'percent_buy':
                case 'deduct_buy':
                case 'percent_duduct_total_buy':
                    this.rows[event.index] = {...this.editingCellRows[event.index]};
                    break;

                default:
                    if (editingCellValue.trim().length > 0)
                        this.rows[event.index] = {...this.editingCellRows[event.index]};
                    else
                        event.preventDefault();
                    break;
            }

            const data = this.rows[event.index];
            let frm = this.$inertia.form(data);
            frm.put(route('gold-percents.update', data.id), {
                errorBag: 'goldPercents',
                preserveScroll: true,
                onSuccess: () => {
                    this.$toast.add({severity: 'success', summary: 'บันทึกข้อมูลเรียบร้อย', life: 5000})
                },
                onError: () => {
                    this.$toast.add({severity: 'error', summary: 'เกิดข้อผิดพลาด', detail: this.errors, life: 5000})
                }
            })
        },
        onCellEdit(newValue, props) {
            if (!this.editingCellRows[props.index]) {
                this.editingCellRows[props.index] = {...props.data};
            }

            this.editingCellRows[props.index][props.column.props.field] = newValue;
        },
        isPositiveInteger(val) {
            let str = String(val);
            str = str.trim();
            if (!str) {
                return false;
            }
            str = str.replace(/^0+/, "") || "0";
            var n = Math.floor(Number(str));
            return n !== Infinity && String(n) === str && n >= 0;
        },
    },
}
</script>
