<template>
    <jet-form-section @submitted="updateCompanyData">
        <template #title>
            ข้อมูลบริษัท
        </template>

        <template #description>
            อัพเดทข้อมูลพื้นฐานของบริษัท
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div class="col-span-12">
                <input type="file" class="hidden"
                       ref="photo"
                       @change="updatePhotoPreview">

                <jet-label for="photo" value="Photo"/>

                <!-- Current Profile Photo -->
                <div class="mt-2" v-show="! photoPreview">
                    <img :src="company.logo_url" :alt="company.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" v-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <jet-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Logo
                </jet-secondary-button>

                <jet-secondary-button type="button" class="mt-2" @click.prevent="deletePhoto"
                                      v-if="company.logo_url">
                    Remove Photo
                </jet-secondary-button>

                <jet-input-error :message="form.errors.photo" class="mt-2"/>
            </div>

            <!--             Name-->
            <div class="col-span-6 sm:col-span-8">
                <jet-label for="name" value="ชื่อบริษัท"/>
                <jet-input id="name" type="text" class="mt-1 block w-full"
                           v-model="form.name" autocomplete="name"/>
                <jet-input-error :message="form.errors.name" class="mt-2"/>
            </div>
            <div class="col-span-12">
                <jet-label for="addr" value="ที่อยู่"></jet-label>
                <jet-input id="addr" type="text" class="mt-1 block w-full" v-model="form.addr" autocomplete="addr"/>
                <jet-input-error :message="form.errors.addr" class="mt-2"/>
            </div>
            <div class="col-span-6">
                <jet-label for="tax_id" value="เลขผู้เสียภาษี"></jet-label>
                <jet-input id="tax_id" type="text" class="mt-1 block w-full" v-model="form.tax_id" />
                <jet-input-error :message="form.errors.tax_id" class="mt-2"/>
            </div>
            <div class="col-span-6">
                <jet-label for="tel" value="เบอร์โทร"></jet-label>
                <jet-input id="tel" type="text" class="mt-1 block w-full" v-model="form.tel" />
                <jet-input-error :message="form.errors.tel" class="mt-2"/>
            </div>

            <!-- Email -->
            <!--            <div class="col-span-6 sm:col-span-4">-->
            <!--                <jet-label for="email" value="Email" />-->
            <!--                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />-->
            <!--                <jet-input-error :message="form.errors.email" class="mt-2" />-->
            <!--            </div>-->
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

</template>

<script>

import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

export default {
    components: {
        JetActionMessage,

        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
    },

    props: ['company'],

    data() {
        return {
            form: this.$inertia.form({
                _method: 'POST',
                name: this.company.name,
                addr: this.company.addr,
                tel: this.company.tel,
                tax_id: this.company.tax_id,
                photo: null,
            }),

            photoPreview: null,
        }
    },

    methods: {
        updateCompanyData() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('company.store'), {
                    errorBag: 'companyData',
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
            this.$inertia.delete(route('current-user-photo.destroy'), {
                preserveScroll: true,
                onSuccess: () => (this.photoPreview = null),
            });
        },
    },
}
</script>
