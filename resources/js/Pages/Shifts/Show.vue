<template>
    <app-layout>
        <div v-if="shiftClosed">
            ยังไม่มีกะทำงานที่เปิดอยู่
        </div>

        <form @submit.prevent="submit" v-if="!shiftClosed">
            <div class="text-2xl mb-3">สรุปกะทำงาน {{ $filters.datetime(shift.opened_at) }} -
                {{ $filters.datetime(shift.closed_at) }}
            </div>
            <div class="p-grid p-fluid">
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
                                            <label class="p-col-fixed w-28">เงินสดรับ</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_in" inputClass="text-right" disabled/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">เงินสดจ่าย</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_out" inputClass="text-right" disabled/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">รวม</label>
                                            <div class="p-col">
                                                <InputNumber :modelValue="cashTotal" inputClass="text-right"
                                                             disabled/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">นับจริง</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_count"
                                                             inputClass="text-right"
                                                             />
                                                <InputError v-model="v.form.cash_count.$errors"/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">ส่งเข้าเซฟ</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_to_safe"
                                                             inputClass="text-right"
                                                             />
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">ส่งธนาคาร</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_to_bank"
                                                             inputClass="text-right"
                                                             />
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-28">ยอดยกไป</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.cash_end"
                                                             inputClass="text-right"
                                                />
                                                <InputError v-model="v.form.cash_end.$errors"/>
                                            </div>
                                        </div>
                                        <Divider/>
                                        <h5>เงินโอน</h5>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-40">โอนเข้า</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.bank_transfer_in" inputClass="text-right"
                                                             disabled/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-40">โอนออก</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.bank_transfer_out" inputClass="text-right"
                                                             disabled/>
                                            </div>
                                        </div>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-40">รวมเงินโอน</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.bank_transfer_end" inputClass="text-right"
                                                             disabled/>
                                            </div>
                                        </div>
                                        <Divider/>
                                        <h5>บัตรเครดิต</h5>
                                        <div class="p-field p-formgrid p-grid">
                                            <label class="p-col-fixed w-40">ยอดรวมบัตรเครดิต</label>
                                            <div class="p-col">
                                                <InputNumber v-model="form.card" inputClass="text-right" disabled/>
                                            </div>
                                        </div>
                                    </template>
                                </Card>
                            </div>
                        </div>
                        <!--                        <div class="p-grid">-->
                        <!--                            <div class="p-col">-->
                        <!--                                <Card>-->
                        <!--                                    <template #content>-->
                        <!--                                        <h5>ขายฝาก</h5>-->
                        <!--                                        <label class="w-20">รับขายฝาก</label>-->
                        <!--                                        <InputNumber v-model="form.pawn_amount" disabled-->
                        <!--                                                     inputClass="text-right"/>-->
                        <!--                                        <label class="w-20">จำนวนห่อ</label>-->
                        <!--                                        <InputNumber v-model="form.pawn_count" disabled-->
                        <!--                                                     inputClass="text-right"/>-->
                        <!--                                    </template>-->
                        <!--                                </Card>-->
                        <!--                            </div>-->
                        <!--                            <div class="p-col">-->
                        <!--                                <Card>-->
                        <!--                                    <template #content>-->
                        <!--                                        <h5>ออมทอง</h5>-->
                        <!--                                        <label class="w-20">ส่งออม</label>-->
                        <!--                                        <InputNumber v-model="form.saving_amount" disabled-->
                        <!--                                                     inputClass="text-right"/>-->
                        <!--                                        <label class="w-20">จำนวนรายการ</label>-->
                        <!--                                        <InputNumber v-model="form.saving_count" disabled-->
                        <!--                                                     inputClass="text-right"/>-->
                        <!--                                    </template>-->
                        <!--                                </Card>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <Card>
                            <template #content>
                                <h5>น้ำหนักชั่งจริง</h5>
                                <div class="p-grid">
                                    <label class="p-col-fixed w-40"></label>
                                    <div class="p-col text-right">ตามบิล</div>
                                    <div class="p-col text-right">ชั่งจริง</div>
                                </div>
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
                            </template>
                        </Card>


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
                                    <Calendar v-model="form.closed_at" disabled></Calendar>
                                </div>
                            </div>
                        </template>
                    </Card>
                    <Card class="p-my-6">
                        <template #title>พนักงาน</template>
                        <template #content>
                            <div class="p-field">
                                <label for="">ผู้เปิดกะ</label>
                                <select-user v-model="form.open_user_id" disabled></select-user>
                            </div>
                            <div class="p-field">
                                <label for="">ผู้ปิดกะ</label>
                                <select-user v-model="form.close_user_id" disabled></select-user>
                                <InputError v-model="v.form.close_user_id.$errors"/>
                            </div>
                        </template>
                    </Card>
                </div>
            </div>
            <div class="flex flex-col space-y-2">
                <InputError v-model="v.form.$errors"/>
                <Button label="ปิดกะ" class="p-button-danger" type="submit"></Button>
            </div>
        </form>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import UseVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'
import SelectUser from "@/A/SelectUser";
import InputError from "@/Jetstream/InputError";

export default {
    name: "Show",
    setup() {
        return {v: UseVuelidate()}
    },
    components: {SelectUser, AppLayout, InputError},
    props: ['shift'],
    data() {
        return {
            form: this.$inertia.form(this.$page.props.shift)
        }
    },
    validations() {
        return {
            form: {
                cash_count: {required},
                cash_end: {required},
                close_user_id: {required}
            }
        }
    },
    watch:{
        'form.cash_count': function() {
            this.calcCashEnd()
        },
        'form.cash_to_safe': function() {
            this.calcCashEnd()
        },
        'form.cash_to_bank': function() {
            this.calcCashEnd()
        }
    },
    computed: {
        cashTotal() {
            return numeral(this.form.cash_begin).add(this.form.cash_in).subtract(this.form.cash_out).value()
        },
        shiftClosed() {
            return this.shift.status === 'close'
        }
    },
    methods: {
        calcCashEnd(e) {
            return this.form.cash_end = numeral(this.form.cash_count)
                .subtract(this.form.cash_to_safe)
                .subtract(this.form.cash_to_bank)
                .value()
        },
        submit() {
            this.v.form.$touch()
            if (this.v.form.$error) return

            this.$confirm.require({
                message: 'ท่านต้องการปิดกะทำงาน?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    this.form.status = 'close';
                    this.form.closed_at = new Date();
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
