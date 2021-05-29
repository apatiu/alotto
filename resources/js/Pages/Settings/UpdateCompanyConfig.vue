<template>
    <jet-form-section @submitted="updateData">
        <template #title>
            ตั้งค่าโปรแกรม
        </template>

        <template #description>
            อัพเดทการตั้งค่าระบบ ใช้กับทุกสาขา
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div class="p-fluid">
                <div class="p-field">
                    <a-label for="bullionPriceDiff" value="ส่วนต่างซื้อ-ขายราคาทองแท่ง"/>
                    <jet-input id="bullionPriceDiff" type="number" step="0.01" class="mt-1 block w-full"
                               v-model="form.bullion_price_diff"/>
                    <FormInputError :message="form.errors.bullionPriceDiff" class="mt-2"/>
                </div>
                <div class="p-field">
                    <a-label for="goldbahtWeight" value="น้ำหนัก/ 1 บาททอง (กรัม)"/>
                    <jet-input id="goldbahtWeight" type="number" step="0.01" class="mt-1 block w-full"
                               v-model="form.gold_baht_weight"/>
                    <FormInputError :message="form.errors.goldbahtWeight" class="mt-2"/>
                </div>
                <div class="p-field-checkbox">
                    <Checkbox id="goldBuyPriceDevideByGoldByWeight" :binary="true"
                              v-model="data.gold_buy_price_devide_by_gold_baht_weight"/>
                    <label for="goldBuyPriceDevideByGoldByWeight">คำนวนราคารับซื้อด้วยการหารน้ำหนักต่อบาท<br/>(ปกติใช้ *.0656)</label>
                    <FormInputError :message="form.errors.goldBuyPriceDevideByGoldbahtWeight" class="mt-2"/>
                </div>

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
</template>

<script>


import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import ALabel from "@/A/ALabel"
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

export default {
    name: "UpdateCompanyConfig",
    components: {
        ALabel,
        JetInput,

        JetFormSection,
        JetInputError,
        JetActionMessage,
        JetSecondaryButton,
    },
    props: ['data'],
    data() {
        return {
            form: this.$inertia.form({
                _method: 'POST',
                gold_baht_weight: this.data.gold_baht_weight,
                bullion_price_diff: this.data.bullion_price_diff,
                gold_buy_price_devide_by_gold_baht_weight: this.data.gold_buy_price_devide_by_gold_baht_weight,
            }),

            photoPreview: null,
        }
    },
    methods: {
        updateData() {
            if (this.$refs.photo) {
                this.form.photo = this.$refs.photo.files[0]
            }

            this.form.post(route('company-config.store'), {
                errorBag: 'company_config',
                preserveScroll: true
            });
        },
    }
}
</script>

<style scoped>

</style>
