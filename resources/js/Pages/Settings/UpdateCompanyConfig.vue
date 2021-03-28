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

            <div class="col-span-6 sm:col-span-8">
                <jet-label for="bullionPriceDiff" value="ส่วนต่างซื้อ-ขายราคาทองแท่ง"/>
                <jet-input id="bullionPriceDiff" type="number" step="0.01" class="mt-1 block w-full"
                           v-model="form.bullion_price_diff"/>
                <jet-input-error :message="form.errors.bullionPriceDiff" class="mt-2"/>
            </div>
            <div class="col-span-2">
                <jet-label for="goldbahtWeight" value="น้ำหนัก/ 1 บาททอง (กรัม)"/>
                <jet-input id="goldbahtWeight" type="number" step="0.01" class="mt-1 block w-full"
                           v-model="form.gold_baht_weight"/>
                <jet-input-error :message="form.errors.goldbahtWeight" class="mt-2"/>
            </div>
            <div class="col-span-4 pt-5">
                <jet-checkbox id="goldBuyPriceDevideByGoldByWeight"
                              :value="data.gold_buy_price_devide_by_gold_baht_weight"
                              v-model:checked="form.gold_buy_price_devide_by_gold_baht_weight"
                              class="mt-1"
                              autocomplete="addr"/>
                <label for="goldBuyPriceDevideByGoldByWeight" class="ml-2">คำนวนราคารับซื้อด้วยการหารน้ำหนักต่อบาท<br/>(ปกติใช้ *.0656)</label>
                <jet-input-error :message="form.errors.goldBuyPriceDevideByGoldbahtWeight" class="mt-2"/>
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
import JetLabel from '@/Jetstream/Label'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'

export default {
    name: "UpdateCompanyConfig",
    components: {
        JetLabel,
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
