<template>
    <app-layout>
        <div v-if="shiftClosed">
            ยังไม่มีกะทำงานที่เปิดอยู่
        </div>

        <form @submit.prevent="submit" v-if="!shiftClosed">
            <div class="text-2xl mb-3">สรุปกะทำงาน {{ $filters.date(shift.d) }}</div>
            <div class="p-grid">
                <div class="p-sm-8">
                    <div class="p-fluid">
                        <div class="p-field p-formgrid p-grid">
                            <label class="p-col-fixed w-40">เงินสดในลิ้นชักต้นวัน</label>
                            <div class="p-col">
                                <InputNumber v-model="form.cash_begin" inputClass="text-right" disabled/>
                            </div>
                        </div>
                        <div class="p-field p-formgrid p-grid">
                            <label class="p-col-fixed w-40">เงินสดรับจ่าย</label>
                            <div class="p-col">
                                <InputNumber v-model="form.cash" inputClass="text-right" disabled/>
                            </div>
                        </div>
                        <div class="p-field p-formgrid p-grid">
                            <label class="p-col-fixed w-40">ส่งเข้าเซฟ</label>
                            <div class="p-col">
                                <InputNumber v-model="form.cash_to_safe" inputClass="text-right"/>
                            </div>
                        </div>
                        <div class="p-field p-formgrid p-grid">
                            <label class="p-col-fixed w-40">ส่งธนาคาร</label>
                            <div class="p-col">
                                <InputNumber v-model="form.cash_to_bank" inputClass="text-right"/>
                            </div>
                        </div>
                        <div class="p-field p-formgrid p-grid">
                            <label class="p-col-fixed w-40">ยอดเงินสดยกไป</label>
                            <div class="p-col">
                                <InputNumber v-model="form.cash_end"
                                             inputClass="text-right"
                                             disabled/>
                            </div>
                        </div>
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
                    <Button label="ปิดกะ" class="p-button-danger" type="submit"></Button>
                </div>
            </div>
        </form>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "Show",
    components: {AppLayout},
    props: ['shift'],
    data() {
        return {
            form: this.$inertia.form(this.$page.props.shift)
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
