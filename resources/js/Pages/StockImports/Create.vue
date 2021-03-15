<template>
    <div class="py-4 px-6">
        <h1 class="text-2xl mb-6">สร้างใบรับสินค้า</h1>
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-1">
                <text-input
                    v-model="form.id"
                    :error="form.errors.id"
                    label="เลขที่"
                    disabled
                />
            </div>
            <div class="col-start-5">
                <label for="">วันที่นำเข้า</label>
                <Calendar v-model="form.d" dateFormat="dd-mm-yy"></Calendar>
                <input-error :message="form.errors.d"></input-error>
            </div>
            <div class="col-start-6">
                <select-supplier
                    v-model="form.sup_name"
                    label="ผู้จำหน่าย"
                    :error="form.errors.sup_name">
                </select-supplier>
            </div>
            <div class="col-span-6 text-right">
                <Button @click="createLine">Add</Button>
            </div>
            <div class="col-span-6">
                <DataTable :value="lines">
                    <Column field="name" header="ชื่อสินค้า"></Column>
                </DataTable>
            </div>
        </div>
        <div class="pt-4 flex justify-end">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3 inline-flex">
                บันทึกข้อมูลแล้ว.
            </jet-action-message>
            <Button @click="$inertia.visit('/stock-imports')" class="p-mr-2 p-button-text">ยกเลิก</Button>
            <Button :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="store">
                Save
            </Button>
        </div>
    </div>
    <Dialog header="เพิ่มสินค้า" v-model:visible="creatingLine" :modal="true" :style="{width:'80vw'}">
        <div class="grid grid-cols-6 gap-4">
            <div class="col-span-1">
                <a-input
                    v-model="line.product_id"
                    label="รหัสสินค้า"></a-input>
            </div>
            <div class="col-span-1">
                <select-gold-percent v-model="line.gold_percent"/>
            </div>
            <div class="col-span-1">
                <a-input
                    v-model="line.product_group"
                    label="กลุ่มสินค้า"></a-input>
            </div>
            <div class="col-span-3">
                <a-input
                    v-model="line.product_design"
                    label="ลาย"></a-input>
            </div>
            <div class="col-span-6">
                <a-input
                    v-model="line.name"
                    label="ชื่อสินค้า"></a-input>
            </div>
        </div>
        <template #footer>
            <Button label="ยกเลิก" icon="pi pi-times" class="p-button-text" @click="creatingLine=false"/>
            <Button label="บันทึก" icon="pi pi-check" autofocus/>
        </template>
    </Dialog>
</template>

<script>
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

import JetFormSection from "@/Jetstream/FormSection";
import JetActionMessage from "@/Jetstream/ActionMessage";
import Input from "@/Jetstream/Input";
import AInput from "@/A/AInput";
import AppLayout from "@/Layouts/AppLayout";
import SelectSupplier from "@/Shared/SelectSupplier";
import ATable from "@/A/ATable";
import InputError from "@/Jetstream/InputError";
import SelectGoldPercent from "@/A/SelectGoldPercent";

export default {
    metaInfo: {title: 'Create Suppliers'},
    components: {
        SelectGoldPercent,
        InputError,
        ATable,
        SelectSupplier,
        AInput,
        Input,
        JetFormSection,
        JetActionMessage,
        LoadingButton,
        SelectInput,
        TextInput,
    },
    layout: AppLayout,
    props: ['item', 'gold_percents'],
    data() {
        return {
            form: this.$inertia.form({
                id: this.item.id ?? null,
                d: this.item.d,
                sup_name: this.item.sup_name ?? null,
                lines: this.item.lines,
            }),
            creatingLine: false,
            line: {
                product_id: null,
                gold_percent: null,
                product_group: null,
            }
        }
    },
    methods: {
        createLine() {
            this.creatingLine = true;
        },
        store() {
            this.form.post(route('stock-imports.store'), {
                errorBag: 'stockImportBag',
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset()
                    this.$emit('close');
                }
            })
        }
    }
}
</script>
