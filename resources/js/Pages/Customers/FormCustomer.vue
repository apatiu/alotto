<template>
    <Dialog
        v-model:visible="visible"
        header="ลูกค้า"
        modal
        :closable="false"
        :closeOnEscape="false">

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
        <template #footer>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3 inline-flex">
                บันทึกข้อมูลแล้ว.
            </jet-action-message>
            <Button @click="$emit('update:visible',false)" class="p-button-text">ยกเลิก</Button>
            <Button color="primary" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="store">
                Save
            </Button>
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

export default {
    metaInfo: {title: 'เพิ่มลูกค้า'},
    components: {
        AInput,
        Input,
        JetFormSection,
        JetActionMessage,

        LoadingButton,
        SelectInput,
        TextInput,
    },
    props: {
        visible: {
            type: Boolean, default: false
        },
        record: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                id: null,
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
        }
    },
    mounted() {
        _.assign(this.form, this.record);
    },
    methods: {
        store() {
            if (this.record.id ?? false) {

            } else {
                this.form.post(route('customers.store'), {
                    errorBag: 'createCustomer',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$emit('created',this.form.data());
                        this.form.reset()
                        this.close();
                    }
                })
            }
        },
        close() {
            this.$emit('update:visible', false)
        }
    }
}
</script>
