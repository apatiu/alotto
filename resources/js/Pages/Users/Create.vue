<template>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <jet-form-section @submitted="store">
            <template #title>
                <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('users')">Users
                </inertia-link>
                <span class="text-indigo-400 font-medium">/</span> Create
            </template>
            <template #form>
                <div class="col-span-6">

                    <text-input
                        id="name"
                        v-model="form.name"
                        :error="form.errors.name" label="Name"/>
                </div>
                <div class="col-span-6">
                    <text-input
                        id="username"
                        v-model="form.username"
                        :error="form.errors.username"
                        label="Username"/>
                </div>
                <div class="col-span-6">
                    <text-input
                        id="password"
                        v-model="form.password"
                        :error="form.errors.password"
                        type="password" autocomplete="new-password"
                        label="Password"/>
                </div>
                <div class="col-span-6">
                    <text-input
                        v-model="form.password_confirmation"
                        :error="form.errors.password_confirmation"
                        type="password" label="Password Confirmation"/>
                </div>
                <div class="col-span-6">
                    <select-input v-model="form.team_id" label="สาขา"
                                  :error="form.errors.team_id">
                        <option v-for="team in $page.props.teams" :value="team.id">
                            {{ team.name }}
                        </option>
                    </select-input>
                </div>
            </template>
            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Saved.
                </jet-action-message>

                <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Save
                </Button>
            </template>
        </jet-form-section>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

import JetFormSection from "@/Jetstream/FormSection";
import JetActionMessage from "@/Jetstream/ActionMessage";

import Input from "@/Jetstream/Input";
import AInput from "@/A/AInput";

export default {
    metaInfo: {title: 'Create User'},
    components: {
        AInput,
        Input,
        JetFormSection,
        JetActionMessage,

        LoadingButton,
        SelectInput,
        TextInput,
    },
    layout: AppLayout,
    remember: 'form',
    data() {
        return {
            form: this.$inertia.form({
                name: null,
                username: null,
                password: null,
                password_confirmation: null,
                team_id: null,
            }),
        }
    },
    methods: {
        store() {
            this.form.post(this.route('users.store'), {
                errorBag: 'createUser',
            })
        },
    },
}
</script>
