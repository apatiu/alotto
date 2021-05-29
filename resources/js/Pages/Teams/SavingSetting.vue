<template>
    <jet-form-section @submitted="updateData">
        <template #title>
            งานขายฝาก
        </template>

        <template #description>
            การตั้งค่าระบบงานขายฝาก แต่ละสาขาตั้งแยกกันได้
        </template>

        <template #form>
            <div class="p-field">
                <label for="">อายุตั๋ว (เดือน)</label>
                <InputNumber v-model="form.life"></InputNumber>
            </div>
            <div class="p-formgroup-inline">
                <div class="p-field">
                    <label for="intDefaultRate">ดอกเบี้ย/เดือน</label>
                    <InputNumber v-model="form.intr_rate"></InputNumber>
                </div>
                <div class="p-field">
                    <label for="intDefaultRate">ดอกเบี้ยขั้นต่ำ</label>
                    <InputNumber v-model="form.min_intr"></InputNumber>
                </div>
            </div>
            <div class="p-field-checkbox mt-4">
                <Checkbox value="true" v-model="form.intr_rate_by_price" :binary="true"></Checkbox>
                <label for="">อัตราดอกเบี้ยไม่คงที่</label>
            </div>
            <div class="p-field" v-show="form.intr_rate_by_price">
                <DataTable :value="form.intr_range_rates"
                           dataKey="id"
                           editMode="cell"
                           class="p-datatable-sm"
                           v-model:editingRows="editingIntrRangRates">
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
                                    @click="removeIntrRangeRate(slotProps.index)"></Button>
                        </template>
                    </Column>
                </DataTable>
                <Button @click="createIntrRangRate" class="p-button-sm w-24 mt-1" icon="pi pi-plus" label="เพิ่ม"/>

            </div>

            <div class="p-field-checkbox mt-8">
                <Checkbox value="true"
                          v-model="form.intr_discount_by_day"
                          :binary="true"></Checkbox>
                <label for="">ส่วนลดตามวัน</label>
            </div>
            <div class="p-field" v-if="form.intr_discount_by_day">

                <DataTable :value="form.intr_discount_rates" class="p-datatable-sm"
                           dataKey="id"
                           editMode="cell">
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
                                    @click="removeIntrDiscountRate(slotProps.index)"></Button>
                        </template>
                    </Column>
                </DataTable>
                <Button @click="createIntrDiscountRate" class="p-button-sm w-24 mt-1"
                        icon="pi pi-plus" label="เพิ่ม" />
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
            form: this.$inertia.form(this.team.pawn_config ?? {
                life: null,
                intr_rate: null,
                min_intr: null,
                intr_rate_by_price: false,
                intr_discount_by_day: false,
                intr_range_rates:[],
                intr_discount_rates:[]
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
