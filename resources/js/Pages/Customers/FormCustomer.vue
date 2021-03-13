<template>
    <form @submit.prevent="submit">
        <dialog-modal :show="show">

            <template #title>เพิ่มลูกค้า</template>
            <template #content>
                <div class="grid-cols-6 gap-6">
                    <div class="col-span-6">
                        <text-input
                                id="name"
                                v-model="form.name"
                                :error="form.errors.name" label="Name"/>
                    </div>
                </div>
            </template>
            <template #footer>
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            type="submit">
                    Save
                </jet-button>
            </template>
        </dialog-modal>

    </form>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
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
                visible: this.show
            }
        },
        methods: {}
    }
</script>
