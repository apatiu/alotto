<template>
    <label for="" v-if="showLabel">ประเภทสินค้า</label>
    <AutoComplete :modelValue="modelValue"
                  @update:modelValue="$emit('update:modelValue',$event)"
                  :suggestions="filteredTypes"
                  @complete="search($event)"
                  field="name"
                  :dropdown="true"
                  :forceSelection="forceSelection"
                  class="w-full"
                  :class="{'p-invalid': errors.length}"
                  placeholder="แบบสินค้า"
    ></AutoComplete>
    <small class="p-error" v-show="errors.length">เลือกแบบสินค้า</small>


</template>

<script>
export default {
    name: "SelectProductType",
    props: {
        modelValue: {default: {}},
        forceSelection: {default: false},
        errors: {default: []},
        showLabel: {type: Boolean, default: true}
    },
    data() {
        return {
            types: null,
            filteredTypes: null,
        }
    },
    created() {
        axios.get(route('api.product-types.index'))
            .then(response => {
                this.types = response.data
            })
    },
    methods: {
        search(event) {
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
