<template>
    <div class="p-field">
        <label for="">ดีไซน์</label>
        <div class="p-inputgroup">
            <Dropdown :modelValue="modelValue"
                      @update:modelValue="$emit('update:modelValue',$event)"
                      optionLabel="name"
                      :options="items"
                      filter></Dropdown>
            <Button icon="pi pi-plus" @click="create" class="p-button-success"></Button>
        </div>
        <dlg-product-design
            ref="dlgProductDesign"
            @hide="load"
        ></dlg-product-design>
    </div>
</template>

<script>
import DlgProductDesign from "@/Pages/ProductDesigns/dlgProductDesign";

export default {
    name: "SelectProductDesign",
    components: {DlgProductDesign},
    props: {
        modelValue: Object,
        productTypeId: {default: null}
    },
    created() {
        this.load()
    },
    data() {
        return {
            items: [],
            filteredItems: null
        }
    },
    watch: {
        productTypeId(val) {
            this.load()
        }
    },
    methods: {
        load() {
            if (this.productTypeId)
                axios.get(route('api.product-designs.filter.type', this.productTypeId))
                    .then(({data}) => {
                        this.items = data
                    })
        },
        create() {
            this.$refs.dlgProductDesign.mode = 'create';
            this.$refs.dlgProductDesign.show();
        },
    }
}
</script>

<style scoped>

</style>
