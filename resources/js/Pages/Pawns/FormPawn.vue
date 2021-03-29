<template>
    <Dialog v-model:visible="visible"
            header="ข้อมูลใบขายฝาก"
            modal
            :closable="false"
            :closeOnEscape="false"
            class="max-w-7xl">
        <div class="grid grid-cols-2 p-fluid gap-4">
            <div>
                <div class="flex space-x-2">
                    <div class="">
                        <label for="">รหัส</label>
                        <InputText v-model="item.id" disabled></InputText>
                    </div>
                    <div v-show="item.prev_id">
                        <label for="">ใบเก่า</label>
                        <InputText v-model="item.prev_id" class="w-60"></InputText>
                    </div>
                    <div v-show="item.next_id">
                        <label for="">ใบใหม่</label>
                        <InputText v-model="item.next_id" class="w-60"></InputText>
                    </div>
                </div>
                <div class="p-field">
                    <select-customer v-model="item.customer" force-selection
                                     :errors="v$.item.customer.$errors"></select-customer>
                </div>
                <div class="flex space-x-2">
                    <div class="p-field flex-1">
                        <label for="">จำนวนเงิน</label>
                        <InputNumber v-model="item.price"
                                     @input="onPriceChange($event)"
                                     disabled></InputNumber>
                        <small class="p-error" v-if="v$.item.price.$errors.length">กรุณาใส่จำนวนเงิน</small>
                    </div>
                    <div class="p-field w-1/4">
                        <label for="">จำนวนเดือน</label>
                        <InputNumber v-model="item.life" showButtons></InputNumber>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <div class="p-field flex-1">
                        <label for="">อัตราดอกเบี้ย</label>
                        <InputNumber v-model="item.int_rate"></InputNumber>
                    </div>
                    <div class="p-field flex-1">
                        <label for="">ดอกเบี้ย/เดือน</label>
                        <InputNumber v-model="item.int_per_month" disabled></InputNumber>
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
                <DataTable :value="item.payments">
                    <Column field="dt"></Column>
                </DataTable>
            </div>
        </div>
        <div class="p-fluid p-form-grid p-grid mt-8">
            <div class="p-col">
                <select-gold-percent v-model="pawnItem.gold_percent"/>
            </div>
            <div class="p-col">
                <select-product-type v-model="pawnItem.product_type"/>
            </div>
            <div class="p-col">
                <label for="">น้ำหนัก (ก.)</label>
                <InputNumber v-model="pawnItem.weight"
                             :minFractionDigits="1"
                             :maxFractionDigits="3"
                             @input="onDetailItemWeightChange"></InputNumber>
            </div>
            <div class="p-col">
                <label for="">ราคา</label>
                <InputNumber v-model="pawnItem.price"
                             class="w-full"/>
            </div>
            <div class="p-col" style="padding-top: 1.9rem;">
                <Button icon="pi pi-plus" class="p-button-icon p-mt" @click="addItem"></Button>
            </div>

        </div>
        <div class="w-full mt-2">
            <DataTable :value="item.items">
                <Column field="gold_percent" header="% ทอง"></Column>
                <Column field="product_type" header="ประเภท"></Column>
                <Column field="weight" header="น้ำหนัก"></Column>
                <Column field="price" header="ราคา"></Column>
                <Column>
                    <template #body="slotProps">
                        <Button icon="pi pi-trash" class="p-button-rounded p-button-text"
                                @click="removeItemItems"></Button>
                    </template>
                </Column>
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
                    <Button label="บันทึก" icon="pi pi-check" @click="save"></Button>
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script>
import SelectCustomer from "@/A/SelectCustomer";
import SelectGoldPercent from "@/A/SelectGoldPercent";
import SelectProductType from "@/A/SelectProductType";
import useVuelidate from '@vuelidate/core';
import {required} from '@vuelidate/validators'
import InputWeight from "@/A/InputWeight";

export default {
    name: "FormPawn",
    setup() {
        return {
            v$: useVuelidate()
        }
    },
    components: {InputWeight, SelectProductType, SelectGoldPercent, SelectCustomer},
    props: ['visible', 'pawnId'],
    data() {
        return {
            item: {},
            customer: {
                name: '',
                phone: null,
                addr: null,
                tax_id: null
            },
            pawnItem: {
                gold_percent: '96',
                product_type: null,
                weight: 0,
                price: 0
            },
            config: {}
        }
    },
    validations() {
        return {
            item: {
                customer: {required},
                price: {required},
            }
        }
    },
    mounted() {
        axios.get('/api/pawn-configs')
            .then(response => {
                this.config = response.data
            })
    },
    watch: {
        visible(val) {
            if (val) {
                if (this.pawnId) {
                    this.item = {}
                } else { //create mode
                    this.item = {
                        life: this.config.pawn_life,
                        int_rate: this.config.pawn_int_default_rate,
                        dt: new Date(),
                        dt_end: moment().add(this.config.pawn_life, 'months').toDate(),
                        items: []
                    }
                    this.clearDetailItem();
                }
            }
        },
    },
    methods: {
        pawnmax(w) {
            let max = (this.config.goldprice - 100) * .96;

            max -= (max * (this.item.life * this.item.int_rate) / 100)
            max = max * .0656 * w;
            max = Math.floor(max / 100) * 100;
            return max
        },
        onDetailItemWeightChange(e) {
            console.log(e.value);
            this.pawnItem.price = this.pawnmax(e.value)
        },
        clearDetailItem() {
            this.pawnItem = {
                gold_percent: '96',
                product_type: null,
                weight: 0,
                price: 0
            }
        },
        addItem() {
            this.item.items.push(_.assign({}, this.pawnItem));
            this.clearDetailItem();
            this.calcPrice();
        },
        removeItemItems(e) {
            this.item.items.splice(e.index,1)
            this.calcPrice();
        },
        calcPrice() {
            let price = 0;
            _.each(this.item.items, (o) => {
                price += o.price;
            })
            this.item.price = price;
            this.onPriceChange({value: price});
        },
        onPriceChange(e) {
            this.item.int_per_month = e.value * this.item.int_rate / 100;
        },
        save() {
            if (this.item.customer) {
                this.item.customer_id = this.item.customer.id;
            }

            this.v$.$touch();
            if (this.v$.$error) return;

            if (!this.item.id) {
                let form = this.$inertia.form(_.assign({}, this.item, this.items));
                axios.post(route('api.pawns.store'), form.data()).then(res => {
                    this.item = res.data
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
