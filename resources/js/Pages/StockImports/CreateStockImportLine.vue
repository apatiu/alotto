<template>
    <Dialog header="เพิ่มสินค้า" v-model:visible="visible"
            :modal="true" position="bottom" style="max-width: 800px; width: 800px">
        <div class="p-fluid">
            <div class="p-grid">
                <div class="p-col-6">
                    <select-product v-model="product"></select-product>
                    <a-input
                        v-model="line.product_name"
                        label="ชื่อสินค้า"></a-input>
                    <div class="p-grid">
                        <div class="p-col">
                            <select-gold-percent
                                v-model="line.gold_percent_id"/>
                            <input-error :model-value="v.line.gold_percent_id.$errors"></input-error>
                        </div>
                        <div class="p-col">
                            <select-product-type
                                v-model="line.product_type"
                                @update:modelValue="line.product_type_id=$event.id"
                            ></select-product-type>
                            <input-error :model-value="v.line.product_type_id.$errors"></input-error>
                        </div>
                    </div>

                    <input-weight :model-value="{weight:  line.product_weight, weightbaht: line.product_weightbaht}"
                                  @update:model-value="updateProductWeight"></input-weight>
                    <div class="p-grid">
                        <div class="p-col">
                            <select-product-design
                                v-model="line.product_design"
                                :product-type-id="line.product_type_id"
                            ></select-product-design>
                        </div>
                        <div class="p-col">
                            <select-product-size
                                v-model="line.product_size"
                                label="ขนาด"></select-product-size>
                        </div>
                    </div>
                </div>
                <div class="p-col-6">
                    <div class="p-grid">
                        <div class="p-col">
                            <a-input-currency v-model="line.qty"
                                              label="จำนวน"
                                              :errors="v.line.qty.$errors"></a-input-currency>
                        </div>
                        <div class="p-col">
                            <a-input-currency
                                v-model="line.avg_cost_per_baht"
                                label="ต้นทุนทอง/บาท"
                                :errors="v.line.avg_cost_per_baht.$errors"></a-input-currency>
                        </div>
                    </div>

                    <div class="p-grid">
                        <div class="p-col">
                            <a-input v-model="line.product_weight_total"
                                     label="น้ำหนักรวม"
                                     disabled></a-input>
                        </div>
                        <div class="p-col">
                            <a-input-currency :model-value="priceGoldTotal"
                                              label="ราคาทองรวม"
                                              disabled></a-input-currency>
                        </div>
                    </div>
                    <div class="p-grid">
                        <div class="p-col">
                            <div class="p-field-radiobutton">
                                <RadioButton v-model="line.sale_with_gold_price"
                                             name="sale_with_gold"
                                             :value="true"
                                             :disabled="line.id"></RadioButton>
                                <label>ราคาเปลี่ยนตามราคาทอง</label>
                            </div>
                            <div class="p-field-radiobutton">
                                <RadioButton v-model="line.sale_with_gold_price"
                                             name="sale_with_gold"
                                             :value="false"></RadioButton>
                                <label>ราคาคงที่</label>
                            </div>
                        </div>
                        <div class="p-col">
                            <div class="p-field-radiobutton">
                                <RadioButton id="wage_by_pcs"
                                             name="wage_type"
                                             :value="true"
                                             v-model="line.wage_by_pcs"
                                             :disabled="line.sale_with_gold_price === false"/>
                                <label for="wage_by_pcs">คิดค่าแรงต่อชิ้น</label>
                            </div>
                            <div class="p-field-radiobutton">
                                <RadioButton id="wage_by_baht"
                                             name="wage_type"
                                             :value="false"
                                             v-model="line.wage_by_pcs"
                                             :disabled="line.sale_with_gold_price === false"/>
                                <label for="wage_by_baht">คิดค่าแรงต่อบาท</label>
                            </div>
                        </div>
                    </div>
                    <div class="p-grid" v-show="line.sale_with_gold_price">
                        <div class="p-field p-col">
                            <label>ค่าแรงทุน</label>
                            <InputNumber v-model="line.cost_wage"
                                         :disabled="line.sale_with_gold_price === false"/>
                            <input-error :model-value="v.line.cost_wage.$errors"></input-error>

                        </div>
                        <div class="p-field p-col">
                            <label>ค่าแรงขาย</label>
                            <InputNumber v-model="line.tag_wage"
                                         :disabled="line.sale_with_gold_price === false"/>
                            <input-error :model-value="v.line.tag_wage.$errors"></input-error>
                        </div>
                    </div>
                    <div class="p-grid" v-show="!line.sale_with_gold_price">
                        <div class="p-field p-col">
                            <label>ราคาทุน</label>
                            <InputNumber v-model="line.cost_price"
                                         :disabled="line.sale_with_gold_price === true"/>
                            <input-error :model-value="v.line.cost_price.$errors"></input-error>
                        </div>
                        <div class="p-field p-col">
                            <label>ราคาขาย</label>
                            <InputNumber v-model="line.tag_price"
                                         :disabled="line.sale_with_gold_price === true"/>
                            <input-error :model-value="v.line.tag_price.$errors"></input-error>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2">
                <label for="" class="">รายละเอียด</label>
                <div class="flex-1">
                    <InputText v-model="line.description" label="รายละเอียด"></InputText>
                </div>
            </div>
        </div>


        <template #footer>
            <div class="flex w-full justify-between pt-3">
                <div class="flex">
                    <Button label="ตรวจสอบสินค้า" class="p-button-success" type="button"
                            @click="checkProduct"></Button>
                    <Button label="รีเซ็ต" class="p-button-secondary"
                            @click="reset"></Button>
                </div>
                <div>
                    <Button label="บันทึก" icon="pi pi-check"
                            :disabled="v.line.$error"
                            @click="$emit('update:modelValue',line)"/>
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script>
import SelectProduct from "@/A/SelectProduct";
import SelectGoldPercent from "@/A/SelectGoldPercent";
import SelectProductType from "@/A/SelectProductType";
import InputWeight from "@/A/InputWeight";
import SelectProductDesign from "@/A/SelectProductDesign";
import AInput from "@/A/AInput";
import SelectProductSize from "@/A/SelectProductSize";
import AInputCurrency from "@/A/AInputCurrency";
import useVuelidate from "@vuelidate/core";
import {required, requiredIf} from '@vuelidate/validators'
import InputError from "@/Jetstream/InputError";

export default {
    name: "CreateStockImportLine",
    components: {
        InputError,
        AInputCurrency,
        SelectProductSize,
        AInput, SelectProduct, SelectProductDesign, InputWeight, SelectProductType, SelectGoldPercent
    },
    setup() {
        return {v: useVuelidate()}
    },
    data() {
        return {
            visible: false,
            product: null,
            checked: false,
            line: {
                product_code: null,
                gold_percent_id: 96,
                product_type: null,
                product_type_id: null,
                product_weight: null,
                product_weightbaht: true,
                product_design_id: null,
                product_size_id: null,
                sale_with_gold_price: true,
                wage_by_pcs: true,
                qty: null,
                avg_cost_per_baht: null,
                cost_wage: null,
                tag_wage: null,
                cost_price: null,
                tag_price: null
            }
        }
    },
    validations() {
        return {
            line: {
                gold_percent_id: {required},
                product_type_id: {required},
                avg_cost_per_baht: {required: requiredIf(this.checked)},
                qty: {
                    required: requiredIf(() => {
                        return this.checked
                    })
                },
                cost_wage: {
                    required: requiredIf(() => {
                        return this.checked && this.line.sale_with_gold_price
                    })
                },
                tag_wage: {
                    required: requiredIf(() => {
                        return this.checked && this.line.sale_with_gold_price
                    })
                },
                cost_price: {
                    required: requiredIf(() => {
                        return this.checked && !this.line.sale_with_gold_price
                    })
                },
                tag_price: {
                    required: requiredIf(() => {
                        return this.checked && !this.line.sale_with_gold_price
                    })
                }
            }
        }
    },
    computed: {
        priceGoldTotal() {
            return ((this.line.avg_cost_per_baht ?? 0) * .0656) * this.line.product_weight_total
        }
    },
    methods: {
        show() {
            this.visible = true
        },
        updateProductWeight(event) {
            this.line.product_weight = event.weight
            this.line.product_weightbaht = event.weightbaht
        },
        checkProduct() {
            if (this.checked) {
                this.v.line.$reset();
                this.v.line.$touch();
            } else {
                this.v.line.$reset();
                this.v.line.$touch();

                let query = _.pickBy(this.line)
                query.weight = query.product_weight
                query.weightbaht = query.product_weightbaht

                axios.post(route('api.check-product'), query)
                    .then(({data}) => {
                        _.assign(this.line, data)
                        this.line.product_code = data.code;
                        this.line.product_name = data.name;
                        this.product = data;
                        this.checked = true
                        this.checkProduct();
                    })
            }

        },
        onError: (errors) => {
            console.log('errors')
            console.log(errors);
        },
        reset() {
            this.line = _.mapValues(this.line, () => null);
            this.line.gold_percent_id = 96
            this.line.sale_with_gold_price = true
            this.line.wage_by_pcs = true
            this.checked = false;
            this.v.line.$touch();
        }
    }
}
</script>

<style scoped>

</style>
