<template>
    <div class="border rounded p-2">
        <div class="p-field m-0 p-0">
            <div class="p-d-flex">
                <div class="p-col-8">
                    <label for="" v-if="showLabel" class="mb-1">ลูกค้า:</label>
                    <div class="flex">
                        <AutoComplete :model-value="modelValue"
                                      @update:modelValue="onUpdateValue($event)"
                                      :suggestions="filteredItems"
                                      @complete="search($event)"
                                      field="name"
                                      class="flex-1"
                                      input-class="flex-1"
                                      :disabled=disabled
                                      force-selection
                        ></AutoComplete>
                        <Button icon="pi pi-plus"
                                class="p-button-icon p-ml-2"
                                @click="creating=true"
                                :disabled=disabled></Button>
                    </div>
                    <div class="flex flex-col mt-1">
                        <template v-if="modelValue">
                            <small>เบอร์โทร: {{ modelValue.phone }}</small>
                            <small>เลขบัตร: {{ modelValue.tax_id }}</small>
                            <small>ที่อยุ่: {{ modelValue.address }}</small>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <InputError :model-value="errors"></InputError>
    </div>
    <form-customer v-model:visible="creating"
                   @created="onUpdateValue"></form-customer>

</template>

<script>
import FormCustomer from "@/Pages/Customers/FormCustomer";
import InputError from "@/Jetstream/InputError";

export default {
    name: "SelectCustomer",
    components: {InputError, FormCustomer},
    props: {
        modelValue: Object,
        forceSelection: {
            type: Boolean,
            default: false
        },
        disabled: {type: Boolean, default: false},
        errors: {default: []},
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
        onUpdateValue(e) {
            this.selected = e
            this.$emit('update:modelValue', e)
        }
    }
}
</script>

<style scoped>

</style>
