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
                    <a-input type="date" v-model="form.d"
                             label="วันที่นำเข้า"
                             :error="form.errors.d"></a-input>
                </div>
                <div class="col-start-6">
                    <select-supplier
                            v-model="form.sup_name"
                            label="ผู้จำหน่าย"
                            :error="form.errors.sup_name">
                    </select-supplier>
                </div>
                <div class="col-span-6">
                    <a-table v-model="form.lines" :headers="headers"></a-table>
                </div>
            </div>
            <template #footer>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3 inline-flex">
                    บันทึกข้อมูลแล้ว.
                </jet-action-message>
                <jet-button color="secondary" @click="$emit('close')">ยกเลิก</jet-button>
                <jet-button color="primary" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="store">
                    Save
                </jet-button>
            </template>
        </panel>
    </div>
</template>

<script>
    import TextInput from '@/Shared/TextInput'
    import SelectInput from '@/Shared/SelectInput'
    import LoadingButton from '@/Shared/LoadingButton'
    import Dropdown from "@/Shared/Dropdown";
    import JetFormSection from "@/Jetstream/FormSection";
    import JetActionMessage from "@/Jetstream/ActionMessage";
    import JetButton from "@/Jetstream/Button";
    import Input from "@/Jetstream/Input";
    import AInput from "@/A/AInput";
    import DialogModal from "@/Jetstream/DialogModal";
    import Panel from "@/A/Panel";
    import AppLayout from "@/Layouts/AppLayout";
    import SelectSupplier from "@/Shared/SelectSupplier";
    import ATable from "@/A/ATable";

    export default {
        metaInfo: {title: 'Create Suppliers'},
        components: {
            ATable,
            SelectSupplier,
            Panel,
            DialogModal,
            AInput,
            Input,
            JetFormSection,
            JetActionMessage,
            JetButton,
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
                }),
                headers: [
                    {name: 'product_name', label: 'สินค้า'},
                    {name: 'product_qty', label: 'จำนวน'}
                ]
            }
        },
        methods: {
            store() {
                this.form.post(route('suppliers.store'), {
                    errorBag: 'supplierBag',
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
