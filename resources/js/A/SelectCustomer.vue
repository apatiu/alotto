<template>
    <div class="space-y-1 mt-6">
        <label for="">ลูกค้า:</label>
        <AutoComplete :modelValue="modelValue"
                      @update:modelValue="$emit('update:modelValue',$event)"
                      :suggestions="filteredItems"
                      @complete="search($event)"
                      field="name"
                      :dropdown="true"
                      forceSelection
        ></AutoComplete>
        <InputText placeholder="เบอร์โทร" :value="modelValue.phone ?? ''"></InputText>
        <InputText placeholder="เลขบัตร" :value="modelValue.tax_id"></InputText>
    </div>
</template>

<script>
export default {
    name: "SelectCustomer",
    props: {
        modelValue: Object,
        productTypeId: {default: null}
    },
    data() {
        return {
            items: this.$page.props.customers,
            filteredItems: null
        }
    },
    methods: {
        search(event) {
            setTimeout(() => {
                if (!event.query.trim().length) {
                    this.filteredItems = this.items
                } else {
                    this.$inertia.reload({
                        data: {
                            filterCustomers: event.query,
                        },
                        only: ['customers'],
                        onSuccess: (e) => {
                            this.filteredItems = this.$page.props.customers
                        }
                    })
                }
            }, 250);
        }
    }
}
</script>

<style scoped>

</style>
