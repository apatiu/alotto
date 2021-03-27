<template>
    <div class="p-field space-y-1">
        <label for="">ลูกค้า:</label>
        <AutoComplete :modelValue="modelValue"
                      @update:modelValue="$emit('update:modelValue',$event)"
                      :suggestions="filteredItems"
                      @complete="search($event)"
                      field="name"
                      :dropdown="true"
                      forceSelection
        ></AutoComplete>
        <InputText placeholder="เบอร์โทร"></InputText>
        <InputText placeholder="เลขบัตร"></InputText>
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
            items: this.$page.props.product_designs,
            filteredItems: null
        }
    },
    methods: {
        search(event) {
            console.log('search');
            setTimeout(() => {
                if (!event.query.trim().length) {
                    this.filteredItems = this.items.filter((item) => {
                        return item.product_type_id === this.productTypeId;
                    });
                } else {
                    this.filteredItems = this.items.filter((item) => {
                        return item.name.toLowerCase().startsWith(event.query.toLowerCase()) &&
                            item.product_type_id === this.productTypeId;
                    });
                }
            }, 250);
        }
    }
}
</script>

<style scoped>

</style>
