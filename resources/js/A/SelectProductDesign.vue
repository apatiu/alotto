<template>
    <span class="p-float-label p-filled">
    <AutoComplete :modelValue="modelValue"
                  @update:modelValue="$emit('update:modelValue',$event)"
                  :suggestions="filteredItems"
                  @complete="search($event)"
                  field="name"
                  :dropdown="true"
                  forceSelection
    ></AutoComplete>
    <label for="">ดีไซน์</label>
    </span>
</template>

<script>
export default {
    name: "SelectProductDesign",
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
