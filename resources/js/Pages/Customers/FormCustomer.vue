<template>
    <Dialog
        v-model:visible="visible"
        header="ลูกค้า"
        modal
        :closable="false"
        :closeOnEscape="false"
        style="width:600px">

        <div class="p-fluid">
            <div class="p-field">
                <a-input
                    v-model="form.id"
                    label="รหัสลูกค้า"
                    disabled/>
            </div>
            <div class="p-field">
                <label>ชื่อลูกค้า</label>
                <InputText v-model="form.name"/>
                <InputError :model-value="v.form.name.$errors"/>
            </div>
            <div class="p-field">
                <label>ที่อยู่</label>
                <InputText v-model="form.address"/>
            </div>
            <div class="p-field">
                <a-input
                    v-model="form.tax_id"
                    label="เลขบัตร"/>
            </div>
            <div class="p-field">
                <a-input
                    v-model="form.phone"
                    label="เบอร์โทร"/>
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
import UseVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators"
import InputError from "@/Jetstream/InputError";

export default {
    name: 'FormCustomer',
    setup() {
        return {
            v: UseVuelidate()
        }
    },
    metaInfo: {title: 'เพิ่มลูกค้า'},
    components: {
        InputError,
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
        data: {
            type: Object,
            default: null
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
    validations() {
        return {
            form: {
                name: {required}
            }
        }
    },
    watch: {
        visible(val) {
            if (val) {
                this.form.reset();
                if (this.data) {
                    _.assign(this.form, this.data)
                }
            }
        }
    },
    methods: {
        store() {
            if (this.data.id ?? false) {
                this.form.put(route('customers.update', this.data.id), {
                    errorBag: 'customer',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$emit('updated', this.form.data());
                        this.form.reset()
                        this.close();
                    }
                })
            } else {
                this.form.post(route('customers.store'), {
                    errorBag: 'createCustomer',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$emit('created', this.form.data());
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
