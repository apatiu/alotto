<template>
    <Dialog
        v-model:visible="visible"
        :style="{width: '450px'}"
        header="ข้อมูลดีไซน์"
        class="p-fluid">

        <div class="p-field">
            <select-product-type v-model="form.product_type"
                                 @update:modelValue="(form.product_type_id = $event.id ?? null)"
                                 :errors="v.form.product_type_id.$errors"
            ></select-product-type>

        </div>
        <div class="p-field">
            <AInput v-model.trim="form.name"
                    label="ชื่อลาย"
                    :errors="v.form.name.$errors"
            ></AInput>
        </div>
        <template #footer>
            <Button label="ยกเลิก" icon="pi pi-times" class="p-button-text" @click="hide"/>
            <Button label="บันทึก" icon="pi pi-check" class="p-button-primary" @click="update"/>
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
import DialogModal from "@/Jetstream/DialogModal";
import SelectProductType from "@/A/SelectProductType";

import useVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'

export default {
    setup() {
        return {
            v: useVuelidate()
        }
    },
    metaInfo: {title: 'Create Suppliers'},
    components: {
        SelectProductType,
        DialogModal,
        AInput,
        Input,
        JetFormSection,
        JetActionMessage,

        LoadingButton,
        SelectInput,
        TextInput,
    },
    data() {
        return {
            visible: false,
            mode: null,
            form: this.$inertia.form({
                product_type_id: null,
                name: null,
                product_type: null
            }),
        }
    },
    validations: {
        form: {
            product_type_id: {required},
            name: {required}
        }
    },
    watch: {
        mode(val) {
            if (val === 'create') {
                this.form.reset()
            }
        },
    },
    methods: {
        show() {
            if (this.mode === 'create') {
                this.form.reset();
                this.v.$reset();
            }
            this.visible = true
        },
        hide() {
            this.visible = false
        },
        update() {
            this.v.$touch()
            if (this.v.$error) return

            if (this.mode === 'update') {
                this.form.put(route('product-designs.update', this.form.id), {
                    errorBag: this.errorsBag,
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$toast.add({
                            severity: 'success',
                            summary: 'สำเร็จแล้ว',
                            detail: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            life: 3000
                        });
                        this.rowDialog = false;
                        this.rows = this.items;
                        this.form.reset()
                    }
                });
            } else {
                axios.post(route('api.product-designs.store'), this.form.data())
                    .then(({data}) => {
                        this.notify('บันทึกข้อมูลแล้ว')
                        this.v.$reset()
                        this.form.reset()
                    })
                    .catch((error) => {
                        this.form.errors = error.response.data.errors
                    });
            }
        }
    }
}
</script>
