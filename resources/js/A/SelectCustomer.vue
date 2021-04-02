<template>
    <div class="p-field">
        <label for="">ลูกค้า:</label>
        <div class="p-d-flex">
            <AutoComplete :modelValue="modelValue"
                          @update:modelValue="$emit('update:modelValue',$event)"
                          :suggestions="filteredItems"
                          @complete="search($event)"
                          field="name"
                          dropdown
                          class="flex-1"
            ></AutoComplete>
            <Button icon="pi pi-plus" class="p-button-icon p-ml-2" @click="creating=true"></Button>
        </div>
    </div>

    <div class="flex space-x-2">
        <small>เบอร์โทร: {{ selected.phone }}</small>
        <small>ที่อยุ่: {{ selected.tax_id }}</small>
    </div>
    <small class="p-error" v-if="errors.length">กรุณาเลือกลูกค้า</small>

    <form-customer v-model:visible="creating"
                   @created="select"></form-customer>


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
        },
        errors: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            selected: {},
            filteredItems: null,
            creating: false
        }
    },
    methods: {
        search(event) {
            setTimeout(() => {
                axios.post('/api/customers/search', {q: event.query})
                    .then(response => {
                        this.filteredItems = response.data
                    });
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
