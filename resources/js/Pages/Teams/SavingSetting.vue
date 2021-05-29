<template>
    <jet-form-section @submitted="updateData">
        <template #title>
            งานออมทอง
        </template>

        <template #description>
            การตั้งค่าระบบงานออมทอง แต่ละสาขาตั้งแยกกันได้
        </template>

        <template #form>
            <div class="p-field">
                <label for="">จำนวนหักเมื่อต้องการถอนเงินคืน</label>
                <InputNumber v-model="form.withdraw_fee_percent"></InputNumber>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <Button :class="{ 'opacity-25': form.processing }"
                    class="p-button-sm"
                    :disabled="form.processing"
                    @click="save">
                Save
            </Button>
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

export default {
    name: "SavingSettings",
    components: {
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
        createIntrRangRate() {
            this.form.intr_range_rates.push({
                min: 0,
                max: 0,
                rate: null
            })
        },
        removeIntrRangeRate(i) {
            this.form.intr_range_rates.splice(i, 1);
        },
        createIntrDiscountRate() {
            this.form.intr_discount_rates.push({
                days: 1,
                rate: 0,
            })
        },
        removeIntrDiscountRate(i) {
            this.form.intr_discount_rates.splice(i, 1);
        },
        save() {
            this.form.post(route('teams.savings.configs.store'), {
                errorBag: 'savingConfig',
                preserveScroll: true,
                onSuccess: (e) => {
                    this.$toast.add({severity:'success', summary: 'บันทึกข้อมูลแล้ว', detail:'การตั้งค่าระบบขายฝาก', life: 3000})
                }
            });
        },
    }
}
</script>

<style scoped>

</style>
