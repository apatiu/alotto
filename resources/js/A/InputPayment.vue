<template>
    <Dialog modal
            header="ชำระเงิน"
            v-model:visible="visible"
            @hide="$emit('update:visible',false)"
            :closeOnEscape="false"
            :closable="true"
            class="min-w">
        <div class="p-d-flex">
            <div class="p-mr-5 w-96 p-pt-3">
                <SelectButton v-model="row.method_id"
                              :options="methods"
                              dataKey="id"
                              optionValue="id"
                              optionLabel="name"
                              class="w-full"></SelectButton>
                <div class="p-fluid p-mt-2">
                    <div class="p-field p-grid" v-if="row.method_id==='bank'">
                        <label for="บัญชี" class="p-col-fixed" style="width:100px;">บัญชีธนาคาร</label>
                        <div class="p-col">
                            <select-bank-account v-model="row.bank_account_id"></select-bank-account>
                            <input-error :errors="v.row.bank_account_id.$errors"></input-error>
                        </div>
                    </div>
                    <div class="p-field p-grid">
                        <label for="" class="p-col-fixed" style="width:100px;">วันที่ทำรายการ</label>
                        <div class="p-col">
                            <Calendar v-model="row.dt"></Calendar>
                        </div>
                    </div>
                    <div class="p-field p-grid">
                        <label for="" class="p-col-fixed" style="width:100px;">จำนวนเงิน</label>
                        <div class="p-col">
                            <InputNumber v-model="row.amount" input-class="w-full" autofocus></InputNumber>
                            <input-error :errors="v.row.amount.$errors"></input-error>
                        </div>
                    </div>
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
                    <template v-for="row in rows">
                        <div>{{ row.method_id }}</div>
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
            row: {
                method_id: 'cash',
                bank_account_id: null,
                amount: null,
                dt: null
            },
        }
    },
    watch: {
        visible(val) {
            if (val) {
                this.row.method_id = 'cash'
                this.row.dt = new Date()
                this.row.amount = this.target;
                this.rows = this.payments ?? [];
            }
        }
    },
    computed: {
        remain() {
            return this.target - _.sumBy(this.rows, 'amount')
        }
    },
    created() {
        axios.get(route('api.payment-methods.index'))
            .then(res => {
                this.methods = res.data
            })
    },
    validations() {
        return {
            row: {
                method_id: {required},
                amount: {required, $autoDirty: true},
                bank_account_id: {
                    required: requiredIf(() => {
                        return this.row.method_id === 'bank'
                    }),
                    $autoDirty: true
                }
            }
        }
    },
    methods: {
        savePayment() {
            this.v.$touch()
            if (this.v.$error) return;

            this.rows.push(this.row);
            this.row = {};

            if (this.remain !== 0) {
                this.row.method_id = 'cash';
                this.row.amount = this.remain
                this.row.dt = new Date()
            } else {
                this.$emit('update:visible', false)
                this.$emit('done', this.rows)
            }
        },

    }
}
</script>

<style scoped>

</style>
