<template>

    <div class="p-field">
        <div class="p-d-flex">
            <div class="p-col-8">
                <label for="" v-if="showLabel" class="mb-1">ลูกค้า:</label>
                <div class="flex">
                    <AutoComplete v-model="selected"
                                  :suggestions="filteredItems"
                                  @complete="search($event)"
                                  @itemSelect="onItemSelect"
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


        <InputError :model-value="errors"></InputError>
    </div>
    <form-customer v-model:visible="creating"
                   @created="onCreated"></form-customer>

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
            selected: this.modelValue,
            filteredItems: null,
            creating: false
        }
    },
    watch: {
        modelValue(val) {
            this.selected = val
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
        onCreated(e) {
            this.filteredItems = e
            this.selected = e
            this.$emit('update:modelValue', e)
        },
        onItemSelect(event) {
            this.$emit('update:modelValue', event.value)
        }
    }
}
</script>

<style scoped>

</style>
