<template>
    <jet-form-section @submitted="updateData">
        <template #title>
            งานขายฝาก
        </template>

        <template #description>
            การตั้งค่าระบบงานขายฝาก ใช้กับทุกสาขา
        </template>

        <template #form>
            <div class="p-field">
                <label for="">อายุตั๋ว (เดือน)</label>
                <inputNumber v-model="formData.pawn_life"></inputNumber>
            </div>
            <div class="p-formgroup-inline">
                <div class="p-field">
                    <label for="intDefaultRate">ดอกเบี้ย/เดือน</label>
                    <InputNumber v-model="formData.int_default_rate"></InputNumber>
                </div>
                <div class="p-field">
                    <label for="intDefaultRate">ดอกเบี้ยขั้นต่ำ</label>
                    <InputNumber v-model="formData.int_min"></InputNumber>
                </div>
            </div>
            <div class="p-field-checkbox">
                <Checkbox value="true" v-model="formData.int_range_rates_enable" :binary="true"></Checkbox>
                <label for="">อัตราดอกเบี้ยไม่คงที่</label>
            </div>
            <div class="p-field" v-show="formData.int_range_rates_enable">

                <DataTable :value="formData.int_range_rates"
                           dataKey="id"
                           editMode="cell"
                           v-model:editingRows="editingIntRangeRateRows">
                    <template #header>
                        <Button @click="createIntRangeRate" class="p-button-sm p-button-outlined" style="width: 60px;">+
                            เพิ่ม
                        </Button>
                    </template>
                    <Column field="min" header="ขั้นต่ำ">
                        <template #editor="slotProps">
                            <InputNumber v-model="slotProps.data[slotProps.column.props.field]"/>
                        </template>
                    </Column>
                    <Column field="max" header="สูงสุด">
                        <template #editor="slotProps">
                            <InputNumber v-model="slotProps.data[slotProps.column.props.field]"/>
                        </template>
                    </Column>
                    <Column field="rate" header="อัตราดอกเบี้ย (%)">
                        <template #editor="slotProps">
                            <InputNumber v-model="slotProps.data[slotProps.column.props.field]"/>
                        </template>
                    </Column>
                    <Column>
                        <template #body="slotProps">
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-text"
                                    @click="removeIntRangeRate(slotProps.index)"></Button>
                        </template>
                    </Column>
                </DataTable>
            </div>

            <div class="p-field-checkbox">
                <Checkbox value="true"
                          v-model="formData.int_discount_rates_enable"
                          :binary="true"></Checkbox>
                <label for="">ส่วนลดตามวัน</label>
            </div>
            <div class="p-field" v-if="formData.int_discount_rates_enable">
                <DataTable :value="formData.int_discount_rates"
                           dataKey="id"
                           editMode="cell">
                    <template #header>
                        <Button @click="createIntDiscountRate" class="p-button-sm p-button-outlined"
                                style="width: 60px;">+
                            เพิ่ม
                        </Button>
                    </template>
                    <Column field="days" header="จำนวนวัน">
                        <template #editor="slotProps">
                            <InputNumber v-model="slotProps.data[slotProps.column.props.field]"
                                         suffix=" วัน"/>
                        </template>
                    </Column>
                    <Column field="rate" header="ส่วนลด">
                        <template #editor="slotProps">
                            <InputNumber v-model="slotProps.data[slotProps.column.props.field]"
                                         suffix=" %"/>
                        </template>
                    </Column>
                    <Column>
                        <template #body="slotProps">
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-text"
                                    @click="removeIntDiscountRate(slotProps.index)"></Button>
                        </template>
                    </Column>
                </DataTable>
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
    name: "PawnSettings",
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
            formData: this.data,
            editingIntRangeRateRows: null,
            form: this.$inertia.form(this.data),
        }
    },
    methods: {
        createIntRangeRate() {
            this.formData.int_range_rates.push({
                min: 0,
                max: 0,
                rate: null
            })
        },
        removeIntRangeRate(i) {
            this.formData.int_range_rates.splice(i, 1);
        },
        createIntDiscountRate() {
            this.formData.int_discount_rates.push({
                days: 1,
                rate: 0,
            })
        },
        removeIntDiscountRate(i) {
            this.formData.int_discount_rates.splice(i, 1);
        },
        save() {
            this.form = _.assign(this.form, this.formData);
            this.form.post(route('pawns-config.store'), {
                errorBag: 'pawns_config',
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
