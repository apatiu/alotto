<template>
    <span class="p-float-label">
    <AutoComplete :modelValue="modelValue"
                  @update:modelValue="$emit('update:modelValue',$event)"
                  :suggestions="filteredTypes"
                  @complete="searchType($event)"
                  field="name"
                  :dropdown="true"
                  forceSelection
    ></AutoComplete>
    <label for="">ประเภทสินค้า</label>
    </span>
</template>

<script>
export default {
    name: "SelectProductType",
    props: ['modelValue'],
    data() {
        return {
            types: this.$page.props.product_types,
            filteredTypes: null
        }
    },
    methods: {
        searchType(event) {
            setTimeout(() => {
                if (!event.query.trim().length) {
                    this.filteredTypes = [...this.types];
                } else {
                    this.filteredTypes = this.types.filter((type) => {
                        return type.name.toLowerCase().startsWith(event.query.toLowerCase());
                    });
                }
            }, 250);
        }
    }
}
</script>

<style scoped>

</style>
