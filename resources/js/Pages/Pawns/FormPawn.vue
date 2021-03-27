<template>
    <Dialog v-model:visible="visible"
            header="ข้อมูลใบขายฝาก"
            modal
            :closable="false"
            :closeOnEscape="false"
            class="w-11/12">
        <div class="grid grid-cols-2 p-fluid gap-4">
            <div>
                <div class="flex space-x-2">
                    <div class="">
                        <label for="">รหัส</label>
                        <InputText v-model="item.id"></InputText>
                    </div>
                    <div v-show="item.prev_id">
                        <label for="">ใบเก่า</label>
                        <InputText v-model="item.id" class="w-60"></InputText>
                    </div>
                    <div v-show="item.next_id">
                        <label for="">ใบใหม่</label>
                        <InputText v-model="item.id" class="w-60"></InputText>
                    </div>
                </div>
                <div class="p-field">
                    <select-customer v-model="customer"></select-customer>
                </div>
                <div class="p-field">
                    <label for="">จำนวนเงิน</label>
                    <InputNumber v-model="item.price"></InputNumber>
                </div>
                <div class="flex space-x-2">
                    <div class="p-field">
                        <label for="">อัตราดอกเบี้ย</label>
                        <InputNumber v-model="item.price"></InputNumber>
                    </div>
                    <div class="p-field">
                        <label for="">ดอกเบี้ย/เดือน</label>
                        <InputNumber v-model="item.price"></InputNumber>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <div class="p-field w-full">
                        <label for="">วันที่รับ</label>
                        <Calendar v-model="item.dt"></Calendar>
                    </div>
                    <div class="p-field w-full">
                        <label for="">วันที่ครบกำหนด</label>
                        <Calendar v-model="item.dt_end"></Calendar>
                    </div>
                </div>
            </div>
            <div>
                <label for="">ความเคลื่อนไหว</label>
                <DataTable :value="item.payments"></DataTable>
            </div>
        </div>
        <label for="">รายการของ</label>
        <div class="flex space-x-2">
            <div class="">
                <select-gold-percent v-model="pawnItem.gold_percent"/>
            </div>
            <div class="flex-1">
                <select-product-type v-model="pawnItem.product_type"/>
            </div>
            <div class="">
                <InputNumber v-model="pawnItem.weight"/>
            </div>
            <div class="">
                <InputNumber v-model="pawnItem.price" class="w-full"/>
            </div>
            <div class="">
                <Button icon="pi pi-plus" class="p-button-icon"></Button>
            </div>

        </div>
        <div class="w-full mt-2">
            <DataTable :value="item.items">
                <Column field="gold_percent"></Column>
            </DataTable>
        </div>
        <template #footer>
            <div class="flex items-center justify-between pt-2">
                <div>
                    <Button label="ต่อดอก" class="p-button-info"></Button>
                    <Button label="เปลี่ยนใบ" class="p-button-warning"></Button>
                    <Button label="ไถ่ถอน" class="p-button-success"></Button>
                    <Button label="คัดออก" class="p-button-danger"></Button>
                </div>
                <div>
                    <Button label="ยกเลิก" class="p-button-text" @click="$emit('update:visible',false)"></Button>
                    <Button label="บันทึก" icon="pi pi-check" @click="$emit('update:pawn-data',item)"></Button>
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script>
import SelectCustomer from "@/A/SelectCustomer";
import SelectGoldPercent from "@/A/SelectGoldPercent";

export default {
    name: "FormPawn",
    components: {SelectGoldPercent, SelectCustomer},
    props: ['visible', 'pawnData'],
    data() {
        return {
            item: this.pawnData,
            customer: {
                name: '',
                phone: null,
                addr: null,
                tax_id: null
            },
            pawnItem: {
                gold_percent: null,
                product_type: null,
                weight: null,
                price: null
            }
        }
    },
    mounted() {
    }
}
</script>

<style scoped>

</style>
