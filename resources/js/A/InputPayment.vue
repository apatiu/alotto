<template>
    <Dialog modal
            header="ชำระเงิน"
            v-model:visible="visible"
            @hide="$emit('update:visible',false)"
            :closeOnEscape="false"
            :closable="true"
            class="min-w">
        <div class="flex-col space-y-5">
            <div class="text-7xl text-center">{{ target }}</div>
            <div class="flex items-center items-stretch space-x-6 h-20" v-if="singleMethod">
                <Button class="text-center text-2xl"
                        label="เงินสด"
                        @click="allCash"></Button>
                <Button class="p-button-warning text-2xl"
                        label="ช่องทางอื่น"
                        @click="singleMethod=false"></Button>
            </div>
            <div v-else>
                <div class="flex space-x-4">
                    <Button icon="pi pi-arrow-circle-left" label="กลับ"
                            class="p-button-text"
                            @click="singleMethod=true"></Button>
                    <TabView>
                        <TabPanel header="เงินสด">
                            <div class="flex">
                                <InputNumber v-model="payment.amount"></InputNumber>
                            </div>
                        </TabPanel>
                    </TabView>
                </div>
                <div class="flex items-center justify-end">
                    <Button label="ลงรายการ" @click="savePayment"></Button>
                </div>
            </div>
        </div>
    </Dialog>
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
        visible: Boolean,
        target: Number,
        payments: Array,
    },
    data() {
        return {
            payments: {},
            methods: null,
            singleMethod: true,
            payment: {}
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
    created() {
        axios.get(route('payment-methods.index'))
            .then(res => {
                this.methods = res.data
            })
    },
    validations() {
        return {}
    },
    methods: {
        allCash() {
            this.payments = [{
                method: 'cash',
                amount: this.target,
                dt: new Date()
            }]
            this.$emit('update:payments', this.payments)
            this.$emit('update:visible', false)
        }

    }
}
</script>

<style scoped>

</style>
