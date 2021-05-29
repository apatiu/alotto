<template>
    <jet-form-section @submitted="updateData">
        <template #title>
            งานออมทอง
        </template>

        <template #description>
            การตั้งค่าระบบงานออมทอง แต่ละสาขาตั้งแยกกันได้
        </template>

        <template #form>
            <div class="p-fluid">
                <div class="p-field">
                    <label for="">เปอร์เซ็นหักเมื่อถอนเงิน</label>
                    <InputPercent class="w-28" v-model="form.withdraw_fee_percent"></InputPercent>
                    <FormInputError :message="form.errors.withdraw_fee_percent"/>
                </div>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                บันทึกแล้ว.
            </jet-action-message>

            <ButtonSave :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="save">
                Save
            </ButtonSave>
        </template>
    </jet-form-section>
</template>

<script>

import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import ALabel from "@/A/ALabel"
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import InputPercent from "../../A/InputPercent";
import InputError from "../../Jetstream/InputError";
import FormInputError from "../../A/FormInputError";
import ButtonSave from "../../A/ButtonSave";

export default {
    name: "SavingSettings",
    components: {
        ButtonSave,
        FormInputError,
        InputError,
        InputPercent,
        ALabel,
        JetInput,
        JetFormSection,
        JetInputError,
        JetActionMessage,
        JetSecondaryButton,
    },
    props: ['team'],
    data() {
        return {
            editingIntrRangeRateRows: null,
            form: this.$inertia.form(this.team.saving_config ?? {
                withdraw_fee_percent: null,
            }),
        }
    },
    methods: {
        save() {
            this.form.post(route('savings-config.store'), {
                errorBag: 'savingConfig',
                preserveScroll: true,
                onSuccess: (e) => {
                    this.$toast.add({
                        severity: 'success',
                        summary: 'บันทึกข้อมูลแล้ว',
                        detail: 'การตั้งค่าระบบออมทอง',
                        life: 3000
                    })
                }
            });
        },
    }
}
</script>

<style scoped>

</style>
