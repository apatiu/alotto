<template>
  <div class="p-fluid">
    <div class="p-field">
      <label class="mb-0">รหัสสินค้า</label>
      <AutoComplete
          v-model="product"
          @item-select="onSelectProduct($event.value)"
          field="code"
          @keyup="onKeyup($event)"
          :suggestions="filteredProducts"
          @complete="searchProduct($event)"
          inputClass="input-product-id"
          optionLabel="code"
          v-bind="$attrs"
      >
        <template #item="{item, index}">
          <div class="flex">
            <div class="w-28">{{ item.code }}</div>
            <div class="w-40">{{ item.name }}</div>
            <div class="w-20 text-right">{{ item.tag_wage ?? item.tag_price }}</div>
          </div>
        </template>
      </AutoComplete>
    </div>
  </div>
</template>

<script>
export default {
  name: "SelectProduct",
  inheritAttrs: false,
  props: {
    modelValue: {default: null},
    resetAfterSelect: {type: Boolean, default: false}
  },
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
      this.$emit('select', e)
      this.filteredProducts = [];
      if (this.resetAfterSelect)
        this.product = null;

    }
  }
}
</script>

<style scoped>

</style>
