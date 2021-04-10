<template>
    <app-layout>
        <div class="text-2xl mb-3">สรุปกะทำงาน {{ $filters.date(shift.d) }}</div>
        <div class="p-grid">
            <div class="p-sm-8">
                <div class="p-fluid">
                    <div class="p-field p-formgrid p-grid">
                        <label class="p-col-fixed w-40">เงินสดในลิ้นชักต้นวัน</label>
                        <div class="p-col">
                            <InputNumber v-model="shift.cash_begin" inputClass="text-right" disabled/>
                        </div>
                    </div>
                    <div class="p-field p-formgrid p-grid">
                        <label class="p-col-fixed w-40">เงินสดรับจ่าย</label>
                        <div class="p-col">
                            <InputNumber v-model="shift.cash" inputClass="text-right" disabled/>
                        </div>
                    </div>
                    <div class="p-field p-formgrid p-grid">
                        <label class="p-col-fixed w-40">ส่งเข้าเซฟ</label>
                        <div class="p-col">
                            <InputNumber v-model="shift.cash_to_safe" inputClass="text-right"/>
                        </div>
                    </div>
                    <div class="p-field p-formgrid p-grid">
                        <label class="p-col-fixed w-40">ส่งธนาคาร</label>
                        <div class="p-col">
                            <InputNumber v-model="shift.cash_to_bank" inputClass="text-right"/>
                        </div>
                    </div>
                    <div class="p-field p-formgrid p-grid">
                        <label class="p-col-fixed w-40">ยอดเงินสดยกไป</label>
                        <div class="p-col">
                            <InputNumber v-model="shift.cash_end" inputClass="text-right"/>
                        </div>
                    </div>
                    <div class="p-field p-formgrid p-grid p-mt-6">
                        <label class="p-col-fixed w-40">ยอดรวมเงินโอน</label>
                        <div class="p-col">
                            <InputNumber v-model="shift.bank" inputClass="text-right"/>
                        </div>
                    </div>
                    <div class="p-field p-formgrid p-grid">
                        <label class="p-col-fixed w-40">ยอดรวมบัตรเครดิต</label>
                        <div class="p-col">
                            <InputNumber v-model="shift.card" inputClass="text-right"/>
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
                                <Calendar v-model="shift.opened_at" disabled></Calendar>
                            </div>
                            <div class="p-field">
                                <label for="">ปิดกะ</label>
                                <Calendar v-model="shift.closed_at"></Calendar>
                            </div>
                        </div>
                    </template>
                </Card>
                <Card class="p-mt-6">
                    <template #title>พนักงาน</template>
                    <template #content>
                        <div class="p-field">
                            <label for="">ผู้เปิดกะ</label>
                            <select-user v-model="shift.opened_user_id" disabled></select-user>
                        </div>
                        <div class="p-field">
                            <label for="">ผู้ปิดกะ</label>
                            <select-user v-model="shift.closed_user_id"></select-user>
                        </div>
                    </template>
                </Card>
            </div>
        </div>

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
            shift: this.$page.props.shift
        }
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
