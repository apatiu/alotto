<template>
    <div class="border rounded p-4">
        <small class="font-bold">ลูกค้า</small>
        <div class="divp-field m-0 p-0">
            <label for="" v-if="showLabel">ลูกค้า:</label>
            <div class="p-d-flex">
                <AutoComplete :modelValue="modelValue"
                              @item-select="onSelect"
                              :suggestions="filteredItems"
                              @complete="search($event)"
                              field="name"
                              dropdown
                              class="flex-1"
                              :disabled=disabled
                ></AutoComplete>
                <Button icon="pi pi-plus"
                        class="p-button-icon p-ml-2"
                        @click="creating=true"
                        :disabled=disabled></Button>
            </div>
            <div class="flex flex-col mt-2">
                <small>เบอร์โทร: {{ selected.phone ?? '' }}</small>
                <small>เลขบัตร: {{ selected.tax_id ?? '' }}</small>
                <small>ที่อยุ่: {{ selected.address ?? '' }}</small>
            </div>
        </div>


        <small class="p-error" v-if="errors.length">กรุณาเลือกลูกค้า</small>
    </div>
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
        disabled: {type: Boolean, default: false},
        errors: {
            type: Array,
            default: []
        },
        showLabel: {type: Boolean, default: true}
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
        onSelect(e) {
            this.selected = e.value;
            this.$emit('update:modelValue', e.value);
        },
        select(e) {
            this.selected = e;
            this.filteredItems = [e]
            this.$emit('update:modelValue', e);
        }
    }
}
</script>

<style scoped>

</style>
