<template>
    <div class="flex-col">
        <template v-for="item in items">
            <div class="border rounded p-1 space-x2 flex">
                <div class="w-1/4">
                    <div class="flex-col">
                        <label for="">ช่องทาง</label>
                        <Dropdown v-model="item.method"
                                  placeholder="ช่องทาง"
                                  :options="methods"
                                  optionLabel="name"
                                  optionValue="id"
                        class="w-full"></Dropdown>
                    </div>
                </div>
                <div class="w-1/4">

                </div>
                <div class="w-1/4">

                </div>
                <div class="w-1/4">
                    <div class="flex-col">
                        <label for="">จำนวนเงิน</label>
                        <InputNumber v-model="item.amount"></InputNumber>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";

export default {
    name: "InputPayment",
    setup() {
        return {
            v$: useVuelidate()
        }
    },
    props: {
        balance: Number,
        payments: Array
    },
    data() {
        return {
            items: [{
                method: 'cash',
                amount: this.balance,
            }],
            methods: null
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
            items: {
                method: {required}
            }
        }
    }
}
</script>

<style scoped>

</style>
