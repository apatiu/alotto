<template>
    <jet-form-section @submitted="updateTeamName">
        <template #title>
            ชื่อสาขา
        </template>

        <template #description>
            ข้อมูลพื้นฐานของสาขา.
        </template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="grid grid-cols-6 gap-4 mt-4">
                <div class="col-span-6">
                    <a-label value="ผู้ดูแลสาขา"/>
                    <div class="flex items-center mt-2">
                        <img class="w-12 h-12 rounded-full object-cover" :src="team.owner.profile_photo_url"
                             :alt="team.owner.name">

                        <div class="ml-4 leading-tight">
                            <div>{{ team.owner.name }}</div>
                            <div class="text-gray-700 text-sm">{{ team.owner.email }}</div>
                        </div>
                    </div>
                </div>


                <!-- Team Name -->
                <div class="col-span-6 sm:col-span-4">
                    <div class="p-field">
                        <label>ชื่อบริษัท</label>
                        <InputText v-model="form.company_name"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.company_name"/>
                    </div>
                    <div class="p-field">
                        <a-label for="name" value="ชื่อสาขา"/>
                        <InputText id="name"
                                   type="text"
                                   v-model="form.name"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.name"/>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <div class="p-field">
                        <label>เลขที่สาขา</label>
                        <InputText v-model="form.branch_number"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.company_name"/>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <div class="p-field">
                        <label>ที่อยู่</label>
                        <InputText v-model="form.addr"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.company_name"/>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <div class="p-field">
                        <label>เลขผู้เสียภาษี</label>
                        <InputText v-model="form.tax_id"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.company_name"/>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-4">
                    <div class="p-field">
                        <label>เบอร์โทร</label>
                        <InputText v-model="form.phone"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.company_name"/>
                    </div>
                </div>
            </div>
        </template>

        <template #actions v-if="permissions.canUpdateTeam">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                บันทึกแล้ว.
            </jet-action-message>

            <ButtonSave type="submit"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
            </ButtonSave>
        </template>
    </jet-form-section>
</template>

<script>
import JetActionMessage from '@/Jetstream/ActionMessage'

import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import ALabel from "@/A/ALabel"
import FormInputError from "@/A/FormInputError";
import ButtonSave from "@/A/ButtonSave";

export default {
    components: {
        ButtonSave,
        FormInputError,
        JetActionMessage,
        JetFormSection,
        JetInput,
        JetInputError,
        ALabel,
    },

    props: ['team', 'permissions'],

    data() {
        return {
            form: this.$inertia.form({
                name: this.team.name,
            })
        }
    },

    methods: {
        updateTeamName() {
            this.form.put(route('teams.update', this.team), {
                errorBag: 'updateTeamName',
                preserveScroll: true
            });
        },
    },
}
</script>
