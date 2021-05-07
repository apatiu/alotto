<template>
    <div class="p-field">
        <label for="">รหัสสินค้า</label>
        <AutoComplete
            :model-value="modelValue"
            @update:modelValue="onSelectProduct($event)"
            field="code"
            @keyup="onKeyup($event)"
            :suggestions="filteredProducts"
            @complete="searchProduct($event)"
            inputClass="input-product-id"
            optionLabel="code">
            <template #item="{item, index}">
                <div class="flex">
                    <div class="w-40">{{ item.product_id }}</div>
                    <div>{{ item.name }}</div>
                </div>
            </template>
        </AutoComplete>
    </div>
</template>

<script>
export default {
    name: "SelectProduct",
    props: ['modelValue'],
    data() {
        return {
            product: null,
            filteredProducts: [],
            timeout: null
        }
    },
    methods: {
        searchProduct(e) {
            this.timeout = setTimeout(() => {
                axios.post(route('api.products.search'), {q: e.query.trim()})
                    .then(({data}) => {
                        this.filteredProducts = data;
                        console.log(data);
                    })
            }, 250)
        },
        onKeyup(e) {
            if (e.keyCode === 13 && (typeof this.product === "string")) {
                if (this.filteredProducts.length === 1) {
                    this.onSelectProduct(this.filteredProducts[0])
                } else {
                    clearTimeout(this.timeout);
                    axios.post(route('api.products.search'), {q: this.product.trim()})
                        .then(({data}) => {
                            if (data.length === 1) {
                                this.onSelectProduct(data[0]);
                            } else if (data = []) {
                                this.notify('ไม่พบสินค้า', 'error')
                            }
                        })
                }
            }
        },
        onSelectProduct(e) {
            this.$emit('update:modelValue', e);
            if ((typeof e) === 'object') {
                this.$emit('select', e)
                this.filteredProducts = [];
                this.product = null;
            }
        }
    }
}
</script>

<style scoped>

</style>
