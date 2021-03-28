<template>
    <div class="flex-col mt-6">
        <label for="">ลูกค้า:</label>
        <div class="flex space-x-2">
            <AutoComplete :modelValue="modelValue"
                          @update:modelValue="$emit('update:modelValue',$event)"
                          :suggestions="filteredItems"
                          @complete="search($event)"
                          field="name"
                          dropdown
                          class="flex-1"
            ></AutoComplete>
            <Button icon="pi pi-plus" class="p-button-icon" @click="creating=true"></Button>

        </div>
        <div class="flex space-x-2">
            <small>เบอร์โทร: {{ modelValue.phone }}</small>
            <small>ที่อยุ่: {{ modelValue.tax_id }}</small>
        </div>

        <form-customer v-model:visible="creating"
                       @created="select"></form-customer>
    </div>

</template>

<script>
import FormCustomer from "@/Pages/Customers/FormCustomer";

export default {
    name: "SelectCustomer",
    components: {FormCustomer},
    props: {
        modelValue: Object,
        forceSelection: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            filteredItems: null,
            creating: false
        }
    },
    methods: {
        search(event) {
            setTimeout(() => {

                this.$inertia.reload({
                    data: {
                        filterCustomers: event.query,
                    },
                    only: ['customers'],
                    onSuccess: (e) => {
                        this.filteredItems = this.$page.props.customers
                    }
                })

            }, 250);
        },
        select(e) {
            this.filteredItems = [e]
            this.$emit('update:modelValue', e);
        }
    }
}
</script>

<style scoped>

</style>
