<template>
    <div class="grid-cols-6 gap-6">
        <div class="col-span-6">
            <text-input
                id="name"
                v-model="formi.name"
                :error="formi.errors.name" label="Name"/>
        </div>
    </div>
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

export default {
    metaInfo: {title: 'Create Customer'},
    components: {
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
    remember: 'form',
    props: ['form'],
    data() {
        return {
            formi: this.form
        }
    },
    watch:{
    }
}
</script>
