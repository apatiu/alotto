<template>
    <label for="">ประเภทสินค้า</label>
    <AutoComplete :modelValue="modelValue"
                  @update:modelValue="$event.name ? $emit('update:modelValue',$event.name) : $emit('update:modelValue',$event)"
                  :suggestions="filteredTypes"
                  @complete="search($event)"
                  field="name"
                  :dropdown="true"
                  :forceSelection="forceSelection"
                  class="w-full"
    ></AutoComplete>


</template>

<script>
export default {
    name: "SelectProductType",
    props: {
        modelValue: {default: {}},
        forceSelection: {default: false}
    },
    data() {
        return {
            types: null,
            filteredTypes: null,
        }
    },
    created() {
        axios.get('api/product_types').then(response => {
            this.types = response.data
        })
    },
    methods:{
        search(event) {
            setTimeout(() => {
                if (!event.query.trim().length) {
                    this.filteredTypes = [...this.types];
                }
                else {
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
