<template>
    <app-layout>
        <div v-if="shiftClosed">
            ยังไม่มีกะทำงานที่เปิดอยู่
        </div>

        <form @submit.prevent="submit" v-if="!shiftClosed">
            <div class="text-2xl mb-3">สรุปกะทำงาน {{ $filters.datetime(shift.opened_at) }} -
                {{ $filters.datetime(shift.closed_at) }}
            </div>
            <div class="p-grid">
                <div class="p-sm-8">
                    <div class="p-fluid">
                        <div class="p-grid">
                            <div class="p-col">
                                <Card>
                                    <template #content>
                                        <h5>เงินสด</h5>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">ต้นวัน</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_begin" inputClass="text-right"
                                                             disabled/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">รับ</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_in" inputClass="text-right" disabled/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">จ่าย</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_out" inputClass="text-right" disabled/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">ส่งเข้าเซฟ</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_to_safe" inputClass="text-right"/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">ส่งธนาคาร</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_to_bank" inputClass="text-right"/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">ยอดยกไป</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_end"
                                                             inputClass="text-right"
                                                             disabled/>
                                            </div>
                                        </div>
                                    </template>
                                </Card>
                            </div>
                            <div class="p-col">
                                <div class="p-field p-formgrid p-grid p-mt-6">
                                    <label class="p-col-fixed w-40">ยอดรวมเงินโอน</label>
                                    <div class="p-col">
                                        <InputNumber v-model="form.bank" inputClass="text-right" disabled/>
                                    </div>
                                </div>
                                <div class="p-field p-formgrid p-grid">
                                    <label class="p-col-fixed w-40">ยอดรวมบัตรเครดิต</label>
                                    <div class="p-col">
                                        <InputNumber v-model="form.card" inputClass="text-right" disabled/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-grid">
                            <div class="p-col">
                                <Card>
                                    <template #content>
                                        <h5>ขายฝาก</h5>
                                        <label class="w-20">รับขายฝาก</label>
                                        <InputNumber v-model="form.pawn_amount" disabled
                                                     inputClass="text-right"/>
                                        <label class="w-20">จำนวนห่อ</label>
                                        <InputNumber v-model="form.pawn_count" disabled
                                                     inputClass="text-right"/>
                                    </template>
                                </Card>
                            </div>
                            <div class="p-col">
                                <Card>
                                    <template #content>
                                        <h5>ออมทอง</h5>
                                        <label class="w-20">ส่งออม</label>
                                        <InputNumber v-model="form.saving_amount" disabled
                                                     inputClass="text-right"/>
                                        <label class="w-20">จำนวนรายการ</label>
                                        <InputNumber v-model="form.saving_count" disabled
                                                     inputClass="text-right"/>
                                    </template>
                                </Card>
                            </div>
                        </div>
                        <div class="p-col text-right mt-10">ชั่งจริง</div>
                        <div class="p-field p-formgrid p-grid">
                            <label class="p-col-fixed w-40">ทองเก่า 96.5</label>
                            <div class="p-col">
                                <InputNumber v-model="form.old_gold_96" inputClass="text-right" disabled/>
                            </div>
                            <div class="p-col">
                                <InputNumber
                                    v-model="form.real_old_gold_96"
                                    inputClass="text-right"></InputNumber>
                            </div>
                        </div>
                        <div class="p-field p-formgrid p-grid">
                            <label class="p-col-fixed w-40">ทองเก่า 90</label>
                            <div class="p-col">
                                <InputNumber
                                    v-model="form.old_gold_90"
                                    inputClass="text-right" disabled/>
                            </div>
                            <div class="p-col">
                                <InputNumber
                                    v-model="form.real_old_gold_90"
                                    inputClass="text-right"></InputNumber>
                            </div>
                        </div>
                        <div class="p-field p-formgrid p-grid">
                            <label class="p-col-fixed w-40">ทองเก่า 99.99</label>
                            <div class="p-col">
                                <InputNumber v-model="form.old_gold_99" inputClass="text-right" disabled/>
                            </div>
                            <div class="p-col">
                                <InputNumber
                                    v-model="form.real_old_gold_99"
                                    inputClass="text-right"></InputNumber>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="p-sm-4">
                    <Card>

                        <template #title>วันเวลา</template>
                        <template #content>
                            <div class="p-fluid">
                                <div class="p-field">
                                    <label for="">เปิดกะ</label>
                                    <Calendar v-model="form.opened_at" disabled></Calendar>
                                </div>
                                <div class="p-field">
                                    <label for="">ปิดกะ</label>
                                    <Calendar v-model="form.closed_at"></Calendar>
                                </div>
                            </div>
                        </template>
                    </Card>
                    <Card class="p-my-6">
                        <template #title>พนักงาน</template>
                        <template #content>
                            <div class="p-field">
                                <label for="">ผู้เปิดกะ</label>
                                <select-user v-model="form.opened_user_id" disabled></select-user>
                            </div>
                            <div class="p-field">
                                <label for="">ผู้ปิดกะ</label>
                                <select-user v-model="form.closed_user_id"></select-user>
                            </div>
                        </template>
                    </Card>

                </div>
            </div>
            <InputNumber prefix="A"></InputNumber>
            <Button label="ปิดกะ" class="p-button-danger" type="submit"></Button>
        </form>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import UseVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'

export default {
    name: "Show",
    setup() {
        const v = UseVuelidate
        return {v}
    },
    components: {AppLayout},
    props: ['shift'],
    data() {
        return {
            form: this.$inertia.form(this.$page.props.shift)
        }
    },
    validations() {
        return {
            form: {}
        }
    },
    computed: {
        shiftClosed() {
            return this.shift.status === 'close'
        }
    },
    methods: {
        submit() {
            this.$confirm.require({
                message: 'ท่านต้องการปิดกะทำงาน?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    this.form.status = 'close';
                    this.form.closed_at = moment().format();
                    this.form.put(route('shifts.update', this.form.id));
                },
                reject: () => {
                    //callback to execute when user rejects the action
                }
            });

        }
    }
}
</script>

<style scoped>

</style>
