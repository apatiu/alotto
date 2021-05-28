<template>
    <jet-form-section @submitted="createTeam">
        <template #title>
            ข้อมูลสาขา
        </template>

        <template #description>
            สร้างสาขาใหม่.
        </template>

        <template #form>
            <div class="col-span-6">
                <label>ผู้ดูแล</label>
                <div class="flex items-center mt-2">
                    <img class="w-12 h-12 rounded-full object-cover" :src="$page.props.user.profile_photo_url"
                         :alt="$page.props.user.name">

                    <div class="ml-4 leading-tight">
                        <div>{{ $page.props.user.name }}</div>
                        <div class="text-gray-700 text-sm">{{ $page.props.user.email }}</div>
                    </div>
                </div>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <div class="p-field">
                    <label>ชื่อสาขา</label>
                    <InputText id="name" type="text" v-model="form.name" autofocus/>
                    <form-input-error :message="form.errors.name"/>
                </div>
            </div>
        </template>

        <template #actions>
            <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                Create
            </Button>
        </template>
    </jet-form-section>
</template>

<script>

import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import FormInputError from '@/A/FormInputError'
import ALabel from "@/A/ALabel"

export default {
    components: {

        JetFormSection,
        JetInput,
        FormInputError,
        ALabel,
    },

    data() {
        return {
            form: this.$inertia.form({
                name: '',
            })
        }
    },

    methods: {
        createTeam() {
            this.form.post(route('teams.store'), {
                errorBag: 'createTeam',
                preserveScroll: true
            });
        },
    },
}
</script>
