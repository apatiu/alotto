<template>
    <Dialog modal
            header="ชำระเงิน"
            v-model:visible="visible"
            @hide="$emit('update:visible',false)"
            :closeOnEscape="false"
            :closable="true"
            class="min-w">
        <div class="p-d-flex">
            <div class="p-mr-5 w-96">
                <SelectButton v-model="row.method"
                              :options="methods"
                              dataKey="id"
                              optionValue="id"
                              optionLabel="name"
                              class="w-full"></SelectButton>
                <div class="grid grid-cols-2 grid-rows-3 gap-2 grid-flow-row auto-cols-min mt-4">
                    <label for="บัญชี" v-if="row.method==='bank'">บัญชีธนาคาร</label>
                    <select-bank-account v-model="row.bank_account_id"  v-if="row.method==='bank'"></select-bank-account>
                    <label for="">วันที่ทำรายการ</label>
                    <Calendar v-model="row.dt"></Calendar>
                    <label for="">จำนวนเงิน</label>
                    <InputNumber v-model="row.amount" input-class="w-full" autofocus></InputNumber>
                    <input-error :errors="v.row.amount.$errors"></input-error>
                </div>
                <div class="flex items-center justify-end mt-2">
                    <Button label="ลงรายการ" @click="savePayment"></Button>
                </div>
            </div>
            <div class="flex-col w-80">
                <div class="flex justify-between w-full">
                    <div>ยอดเรียกเก็บ</div>
                    <div class="text-right border-b">{{ target }}</div>
                </div>
                <div class="flex justify-between w-full">
                    <div>คงเหลือ</div>
                    <div class="text-right border-b">{{ remain }}</div>
                </div>
                <div class="col-span-2">ยอดจ่าย</div>
                <div class="flex justify-between w-full">
                    <template v-for="payment in payments">
                        <div>{{ row.method }}</div>
                        <div>{{ row.amount }}</div>
                    </template>

                </div>

            </div>
        </div>
    </Dialog>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import {required, requiredIf} from "@vuelidate/validators";
import SelectBankAccount from "@/A/SelectBankAccount";
import InputError from "@/Jetstream/InputError";

export default {
    name: "InputPayment",
    components: {InputError, SelectBankAccount},
    setup() {
        return {
            v: useVuelidate()
        }
    },
    props: {
        visible: Boolean,
        target: Number,
        payments: Array,
    },
    data() {
        return {
            methods: null,
            rows: [],
            row: {},
        }
    },
    watch: {
        visible(val) {
            if (val) {
                this.row.method = 'cash'
                this.row.dt = new Date()
                this.row.amount = this.target;
                this.rows = this.payments ?? [];
            }
        }
    },
    computed: {
        remain() {
            return this.target - _.sumBy(this.payments, 'amount')
        }
    },
    created() {
        axios.get(route('payment-methods.index'))
            .then(res => {
                this.methods = res.data
            })
    },
    validations() {
        return {
            row: {
                amount: {required, $autoDirty: true },
                bank_account_id: {
                    required: requiredIf(this.row.method === 'bank')
                }
            }
        }
    },
    methods: {
        savePayment() {
            if (this.row.method === 'cash') {
                if (this.v.$error) return
            }

            this.rows.push(this.row);
            this.row = {};
            this.$emit('done', this.rows)
            this.$emit('update:visible', false)
        },

    }
}
</script>

<style scoped>

</style>
