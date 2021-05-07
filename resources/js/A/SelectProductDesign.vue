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
            ref="dlgProductDesign"></dlg-product-design>
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
        this.loading = true;
        axios.get('/api/product-designs')
            .then(({data}) => {
                this.items = data
            })
    },
    data() {
        return {
            items: [],
            filteredItems: null
        }
    },
    watch: {
        productTypeId(val) {
            if (val)
                axios.get(route('api.product-designs.filter.type', val))
                    .then(({data}) => {
                        this.items = data
                    })
        }
    },
    methods: {
        create() {
            this.$refs.dlgProductDesign.mode = 'create';
            this.$refs.dlgProductDesign.show();
        },
        search(event) {
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
