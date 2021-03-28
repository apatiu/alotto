<template>
    <dialog-modal
        :show="show"
        :closeable="false" @close="$emit('close')">

        <template #title>สร้างผู้จำหน่าย</template>
        <template #content>
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-2">
                    <text-input
                        v-model="form.id"
                        :error="form.errors.id"
                        label="รหัสผู้จำหน่าย"
                        disabled
                        />
                </div>
                <div class="col-span-6">
                    <text-input
                        v-model="form.name"
                        :error="form.errors.name"
                        label="ชื่อ"/>
                </div>
            </div>
        </template>
        <template #footer>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3 inline-flex">
                บันทึกข้อมูลแล้ว.
            </jet-action-message>
            <Button color="secondary" @click="$emit('close')">ยกเลิก</Button>
            <Button color="primary" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="store">
                Save
            </Button>
        </template>
    </dialog-modal>

</template>

<script>
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

import JetFormSection from "@/Jetstream/FormSection";
import JetActionMessage from "@/Jetstream/ActionMessage";

import Input from "@/Jetstream/Input";
import AInput from "@/A/AInput";
import DialogModal from "@/Jetstream/DialogModal";

export default {
    metaInfo: {title: 'Create Suppliers'},
    components: {
        DialogModal,
        AInput,
        Input,
        JetFormSection,
        JetActionMessage,

        LoadingButton,
        SelectInput,
        TextInput,
    },
    props: ['show'],
    data() {
        return {
            form: this.$inertia.form({
                id: null,
                name: null,
            }),
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
