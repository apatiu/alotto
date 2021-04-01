<template>
    <Dialog modal
            header="ชำระเงิน"
            v-model:visible="visible"
            @hide="$emit('update:visible',false)"
            :closeOnEscape="false"
            :closable="true"
            class="min-w">
        <div class="p-d-flex">
            <div class="p-mr-5 w-80">
                <SelectButton v-model="payment.method"
                              :options="methods"
                              dataKey="id"
                              optionLabel="name"
                              class="w-full"></SelectButton>
                <div class="grid grid-cols-2 gap-2">
                    <label for="บัญชี">บัญชีธนาคาร</label>
                    <select-bank-accounts v-model="payment.bank_account_id"></select-bank-accounts>
                    <label for="">จำนวนเงิน</label>
                    <InputNumber v-model="payment.amount" input-class="w-full"></InputNumber>
                </div>
                <div class="flex items-center justify-end">
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
                        <div>{{ payment.method }}</div>
                        <div>{{ payment.amount }}</div>
                    </template>

                </div>

            </div>
        </div>
    </Dialog>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import {required, requiredIf} from "@vuelidate/validators";
import SelectBankAccounts from "@/A/SelectBankAccounts";

export default {
    name: "InputPayment",
    components: {SelectBankAccounts},
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
            payments: {},
            methods: null,
            payment: {},
            methodId: 0,
        }
    },
    watch: {
        visible(val) {
            if (val) {
                this.payment.amount = this.target;
                this.items = this.payments;
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
            payment: {
                amount: {required},
                bank_account_id: {
                    required: requiredIf(this.payment.methodId === 1)
                }
            }
        }
    },
    methods: {
        savePayment() {
            if (methodId === 0) { //cash
                if (this.v.$error) return
            }

            this.payments.push(this.payment);
            this.payment = {};
            this.$emit('update:done', this.payments)
            this.$emit('update:visible', false)
        },

    }
}
</script>

<style scoped>

</style>
