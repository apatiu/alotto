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


                <div class="col-span-6 sm:col-span-4">
                    <!-- Profile Photo File Input -->
                    <input type="file" class="hidden"
                           ref="photo"
                           @change="updatePhotoPreview">

                    <a-label for="photo" value="โลโก้"/>

                    <!-- Current Profile Photo -->
                    <div class="mt-2" v-show="! photoPreview">
                        <img :src="team.profile_photo_url" :alt="form.name" class="rounded-full h-20 w-20 object-cover">
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div class="mt-2" v-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                    </div>

                    <Button class="mt-2 mr-2 p-button-secondary" type="button" @click.prevent="selectNewPhoto">
                        Select A New Photo
                    </Button>

                    <Button type="button" class="mt-2 p-button-secondary" @click.prevent="deletePhoto"
                            v-if="team.profile_photo_path">
                        Remove Photo
                    </Button>

                    <FormInputError :message="form.errors.photo" class="mt-2"/>
                </div>

                <!-- Team Name -->
                <div class="col-span-6 sm:col-span-4 p-fluid">
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
                    <div class="p-field">
                        <label>เลขที่สาขา</label>
                        <InputText v-model="form.branch_number"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.company_name"/>
                    </div>
                    <div class="p-field">
                        <label>ที่อยู่</label>
                        <InputText v-model="form.addr"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.company_name"/>
                    </div>

                    <div class="p-field">
                        <label>เลขผู้เสียภาษี</label>
                        <InputText v-model="form.tax_id"
                                   :disabled="! permissions.canUpdateTeam"/>
                        <FormInputError :message="form.errors.company_name"/>
                    </div>

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
                company_name: this.team.company_name,
                branch_number: this.team.branch_number,
                addr: this.team.addr,
                tax_id: this.team.tax_id,
                phone: this.team.phone,
                photo: null
            }),
            photoPreview: null,
        }
    },

    methods: {
        updateTeamName() {
            if (this.$refs.photo) {
                this.form.photo = this.$refs.photo.files[0]
            }

            this.form.post(route('teams.update', this.team), {
                errorBag: 'updateTeamName',
                preserveScroll: true
            });
        },

        selectNewPhoto() {
            this.$refs.photo.click();
        },

        updatePhotoPreview() {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };

            reader.readAsDataURL(this.$refs.photo.files[0]);
        },

        deletePhoto() {
            this.$inertia.delete(route('current-team-photo.destroy'), {
                preserveScroll: true,
                onSuccess: () => (this.photoPreview = null),
            });
        },
    },
}
</script>
