<template>
    <Dialog header="เพิ่มสินค้า" v-model:visible="visible"
            :modal="true" position="bottom" style="max-width: 800px">
        <div class="p-fluid p-grid">
            <div class="p-col-9">
                <div class="p-grid pt-8">
                    <div class="p-field p-col-3">
                        <a-input
                            v-model="line.product_code"
                            label="รหัสสินค้า"
                        ></a-input>
                    </div>
                    <div class="p-field p-col-9">
                        <a-input
                            v-model="line.product_name"
                            label="ชื่อสินค้า"></a-input>
                    </div>
                    <div class="p-field p-col-3">
                        <select-gold-percent v-model="line.gold_percent"/>
                    </div>
                    <div class="p-field p-col-3">
                        <select-product-type v-model="line.product_type"></select-product-type>
                    </div>
                    <div class="p-field p-col-4">
                        <input-weight :model-value="{weight:  line.product_weight, weightbaht: line.weightbaht}"
                                      @update:model-value="updateProductWeight"></input-weight>
                    </div>
                    <div class="p-field p-col-9">
                        <select-product-design
                            v-model="line.product_design"
                            :product-type-id="line.product_type_id"
                            ></select-product-design>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input
                            v-model="line.product_size"
                            label="ขนาด"></a-input>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input-currency v-model="v$.line.qty.$model" label="จำนวน"
                                          :error="v$.line.qty.$errors.length ? v$.line.qty.$errors[0].$message : null"></a-input-currency>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input v-model="product_weight_total"
                                 label="น้ำหนักรวม"
                                 disabled></a-input>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input-currency
                            v-model="v$.line.avg_cost_per_baht.$model"
                            label="ต้นทุน/บาท"
                            :error="v$.line.avg_cost_per_baht.$errors.length ? v$.line.avg_cost_per_baht.$errors[0].$message : null"
                        ></a-input-currency>
                    </div>
                </div>
                <div class="p-grid">
                    <div class="p-col-6 p-flex-column">
                        <div class="p-field-radiobutton">
                            <RadioButton v-model="product.sale_with_gold_price"
                                         name="sale_with_gold"
                                         :value="true"
                                         :disabled="product.id"></RadioButton>
                            <label>ราคาเปลี่ยนตามราคาทอง</label>
                        </div>
                        <div class="flex">
                            <div class="p-field-radiobutton">
                                <RadioButton id="wage_by_pcs"
                                             name="wage_type"
                                             :value="true"
                                             v-model="product.wage_by_pcs"
                                             :disabled="product.sale_with_gold_price === false"/>
                                <label for="wage_by_pcs">คิดค่าแรงต่อชิ้น</label>
                            </div>
                            <div class="p-field-radiobutton ml-3">
                                <RadioButton id="wage_by_baht"
                                             name="wage_type"
                                             :value="false"
                                             v-model="product.wage_by_pcs"
                                             :disabled="product.sale_with_gold_price === false"/>
                                <label for="wage_by_baht">คิดค่าแรงต่อบาท</label>
                            </div>
                        </div>
                        <div class="p-field mt-4">
                                <span class="p-float-label">
                                    <InputNumber v-model="v$.line.cost_wage.$model"
                                                 :disabled="product.sale_with_gold_price === false"/>
                                    <label>ค่าแรงทุน</label>
                                </span>
                            <input-error :errors="v$.line.cost_wage.$errors"></input-error>
                        </div>
                        <div class="p-field mt-6">
                                <span class="p-float-label">
                                    <InputNumber v-model="v$.line.tag_wage.$model"
                                                 :disabled="product.sale_with_gold_price === false"/>
                                    <label>ค่าแรงขาย</label>
                                </span>
                            <input-error :errors="v$.line.tag_wage.$errors"/>
                        </div>
                    </div>
                    <div class="p-col-6 p-flex-column justify-end">
                        <div class="p-field-radiobutton">
                            <RadioButton v-model="product.sale_with_gold_price"
                                         name="sale_with_gold"
                                         :value="false"></RadioButton>
                            <label>ราคาคงที่</label>
                        </div>
                        <div class="p-field mt-6">
                                <span class="p-float-label">
                                    <InputNumber v-model="v$.line.cost_price.$model"
                                                 :disabled="product.sale_with_gold_price === true"/>
                                    <label>ราคาทุน</label>
                                </span>
                            <input-error :errors="v$.line.cost_price.$errors"/>
                        </div>
                        <div class="p-field mt-6">
                                <span class="p-float-label">
                                    <InputNumber v-model="v$.line.tag_price.$model"
                                                 :disabled="product.sale_with_gold_price === true"/>
                                    <label>ราคาขาย</label>
                                </span>
                            <input-error :errors="v$.line.tag_price.$errors"/>
                        </div>
                    </div>

                </div>
                <div class="p-grid">
                    <div class="p-col-12 mt-6">
                        <a-input v-model="line.description" label="รายละเอียด"></a-input>
                    </div>
                </div>
            </div>
            <!--            photo-->
            <div class="p-col-3">
                <label>ภาพ:</label>
                <Carousel :value="product.photos" orientation="vertical">
                    <template #item="slotProps">
                    </template>
                </Carousel>
            </div>
        </div>

        <template #footer>
            <div class="flex w-full justify-between pt-3">
                <div class="flex">
                    <Button label="ตรวจสอบ" class="p-button-success" type="button"
                            @click="checkProduct"></Button>
                    <div class="flex items-center">
                        <template v-if="productChecked">
                            <Tag class="p-mr-2" icon="pi pi-check" value="ผ่าน" severity="success"></Tag>
                            <span class="text-blue-800" v-if="product.id">สินค้าในสต้อก</span>
                            <span class="text-red-800" v-else>สินค้าใหม่</span>
                        </template>
                        <template v-else>
                            <span class="text-green-700">กรุณาตรวจสอบให้ผ่าน ก่อนกดบันทึก</span>
                        </template>
                    </div>
                </div>
                <div>
                    <Button label="ปิด" icon="pi pi-times"
                            class="p-button-text"
                            @click="creatingLine=false"/>
                    <Button label="บันทึก" icon="pi pi-check"
                            :disabled="!productChecked || v$.line.$error"
                            @click="storeLine"/>
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script>
export default {
    name: "CreateStockImportLine",
    data() {
        return {
            visible: false,
            line: {}
        }
    },
    methods: {
        show() {
            this.visible = true
        },
        updateProductWeight(event) {
            this.product.weight = event.weight
            this.product.weightbaht = event.weightbaht
        },
    }
}
</script>

<style scoped>

</style>
