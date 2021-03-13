<template>
    <dialog-modal
        :show="show"
        :closeable="false" @close="$emit('close')">

        <template #title>เพิ่มลูกค้า</template>
        <template #content>
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-2">
                    <text-input
                        v-model="form.id"
                        :error="form.errors.id"
                        label="รหัสลูกค้า"
                        disabled/>
                </div>
                <div class="col-span-6">
                    <text-input
                        v-model="form.name"
                        :error="form.errors.name" label="ชื่อลูกค้า"/>
                </div>
            </div>
        </template>
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
    </dialog-modal>

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

export default {
    metaInfo: {title: 'Create Customer'},
    components: {
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
    props: ['show'],
    data() {
        return {
            form: this.$inertia.form({
                initial: null,
                name: null,
                team_id: null,
                contact_person: null,
                birthday: null,
                tax_id: null,
                address: null,
                city: null,
                district: null,
                province: null,
                country: null,
                postal_code: null,
                idcard_start: null,
                idcard_end: null,
                idcard_place: null,
                email: null,
                phone: null,
            }),
            mode: 'create',
        }
    },
    watch: {
        editId: function (val) {
            if (val) {
                this.mode = 'edit'
                this.$inertia.get(route('customers.show',this.editId,{
                    preserveState: true
                }))
            } else {
                this.mode = 'create';
            }
        }
    },
    methods: {
        store() {
            this.form.post(route('customers.store'), {
                errorBag: 'createCustomer',
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
