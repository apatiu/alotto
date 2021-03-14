<template>
    <div class="pt-2 pr-4">
        <panel>
            <template #header>
                <h1>สร้างใบรับสินค้า</h1>
            </template>


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
            <template #footer>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3 inline-flex">
                    บันทึกข้อมูลแล้ว.
                </jet-action-message>

                <Button @click="$inertia.visit('/stock-imports')" class="p-mr-2 p-button-text">ยกเลิก</Button>
                <Button :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="store">
                    Save
                </Button>
            </template>
        </panel>
    </div>
    <Dialog header="เพิ่มสินค้า" v-model:visible="creatingLine" :modal="true" :style="{width:'80vw'}">
        <div class="grid grid-cols-6 gap-4">
            <div class="col-span-1">
                <a-input
                    v-model="line.id"
                    label="รหัสสินค้า"></a-input>
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
import Dropdown from "@/Shared/Dropdown";
import JetFormSection from "@/Jetstream/FormSection";
import JetActionMessage from "@/Jetstream/ActionMessage";
import Input from "@/Jetstream/Input";
import AInput from "@/A/AInput";
import Panel from "@/A/Panel";
import AppLayout from "@/Layouts/AppLayout";
import SelectSupplier from "@/Shared/SelectSupplier";
import ATable from "@/A/ATable";
import InputError from "@/Jetstream/InputError";

export default {
    metaInfo: {title: 'Create Suppliers'},
    components: {
        InputError,
        ATable,
        SelectSupplier,
        Panel,
        AInput,
        Input,
        JetFormSection,
        JetActionMessage,
        Dropdown,
        LoadingButton,
        SelectInput,
        TextInput,
    },
    layout: AppLayout,
    props: ['item'],
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
                id: null,
                name: null
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
