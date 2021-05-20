<template>
    <Dialog :visible="visible"
            @update:visible="$emit('update:visible',$event)"
            header="รับซื้อรางวัล">
        <div class="flex-col space-y-2 p-fluid">
            <select-customer v-model="form.customer"
                             :errors="v.form.customer_id.$errors"/>
            <div class="flex space-x-2">
                <div class="p-field">
                    <label>หมายเลข</label>
                    <InputText v-model="form.number" autofocus></InputText>
                    <InputError v-model="v.form.number.$errors"/>
                </div>
                <SelectLotterPrize
                    v-model="form.prize_id"
                    :errors="v.form.prize_id.$errors"/>
                <div class="p-field">
                    <label>มูลค่ารางวัล</label>
                    <InputBaht v-model="form.prize_amount"/>
                    <InputError v-model="v.form.prize_amount.$errors"/>
                </div>
                <div class="p-field">
                    <label>รับซื้อ</label>
                    <InputBaht v-model="form.price_buy"/>
                    <InputError v-model="v.form.price_buy.$errors"/>
                </div>
            </div>
        </div>

        <template #footer>
            <Button label="บันทึก" icon="pi pi-check" @click="getPayments"/>
        </template>
    </Dialog>
</template>

<script>
import InputError from "@/Jetstream/InputError";
import SelectCustomer from "@/A/SelectCustomer";
import InputBaht from "@/A/InputBaht";
import SelectLotterPrize from "@/A/SelectLotterPrize";
import UseVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators"

export default {
    name: "BuyLottery",
    setup() {
        return {
            v: UseVuelidate()
        }
    },
    components: {SelectLotterPrize, InputBaht, SelectCustomer, InputError},
    props: ['visible'],
    data() {
        return {
            form: this.$inertia.form({
                customer_id: null,
                customer: null,
                dt_buy: new Date(),
                number: null,
                qty: 1,
                prize_id: null,
                prize_amount: null,
                price_buy: null,
            })
        }
    },
    validations() {
        return {
            form: {
                customer_id: {required},
                dt_buy: {required},
                number: {required},
                qty: {required},
                prize_id: {required},
                prize_amount: {required},
                price_buy: {required},
            }
        }
    },
    methods: {
        getPayments() {
            this.v.form.$touch();
            if (this.v.form.$error) return

        }
    }
}
</script>

<style scoped>

</style>
