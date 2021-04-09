<template>
    <div class="py-4 px-6">
        <div class="flex justify-between">
            <h1 class="text-2xl mb-6">สร้างใบรับสินค้า</h1>
        </div>
        <div class="grid grid-cols-6 gap-8">
            <div class="p-field col-span-1">
                <text-input
                    v-model="form.id"
                    :error="form.errors.id"
                    label="เลขที่"
                    disabled
                />
            </div>
            <div class="col-start-5">
                <label>วันที่นำเข้า</label>
                <Calendar v-model="form.dt"
                          dateFormat="dd-mm-yy" class="w-full"></Calendar>
                <input-error :message="form.errors.dt"></input-error>
            </div>
            <div class="col-start-6">
                <select-supplier
                    v-model="form.sup_name"
                    label="ผู้จำหน่าย"
                    :error="form.errors.sup_name">
                </select-supplier>
            </div>
        </div>

        <div class="text-right" v-if="form.errors">
            <p v-for="item in form.errors" class="p-error">{{ item }}</p>
        </div>
        <div class="pt-4 flex justify-end">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3 inline-flex">
                บันทึกข้อมูลแล้ว.
            </jet-action-message>
            <Button @click="$inertia.visit('/stock-imports')" class="p-mr-2 p-button-text">ยกเลิก</Button>
            <Button :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="store">
                Save
            </Button>
        </div>
    </div>
    <Dialog header="เพิ่มสินค้า" v-model:visible="creatingLine"
            :modal="true" position="bottom" style="max-width: 800px">
        <div class="p-fluid p-grid">
            <div class="p-col-9">
                <div class="p-grid pt-8">
                    <div class="p-field p-col-3">
                        <a-input
                            v-model="product.product_id"
                            label="รหัสสินค้า"
                        ></a-input>
                    </div>
                    <div class="p-field p-col-9">
                        <a-input
                            v-model="product.name"
                            label="ชื่อสินค้า"></a-input>
                    </div>
                    <div class="p-field p-col-3">
                        <select-gold-percent v-model="product.gold_percent"/>
                        <small class="p-error"
                               v-if="errors.lineBag && errors.lineBag.gold_percent">{{ errors.lineBag.gold_percent }}
                        </small>
                    </div>
                    <div class="p-field p-col-3">
                        <select-product-type v-model="product.product_type"></select-product-type>
                        <small class="p-error"
                               v-if="errors.lineBag && errors.lineBag.product_type_id">{{
                                errors.lineBag.product_type_id
                            }}
                        </small>
                    </div>
                    <div class="p-field p-col-4">
                        <input-weight v-model="productWeight"></input-weight>
                    </div>
                    <div class="p-field p-col-9">
                        <select-product-design
                            v-model="product.product_design"
                            :product-type-id="product.product_type_id"
                            label="ดีไซน์"></select-product-design>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input
                            v-model="product.size"
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
                    <Button label="ตรวจสอบ" class="p-button-success"
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
                            :disabled="!productChecked || v$.$error"
                            @click="storeLine"/>
                </div>
            </div>
        </template>
    </Dialog>
</template>

<script>
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

import JetFormSection from "@/Jetstream/FormSection";
import JetActionMessage from "@/Jetstream/ActionMessage";
import Input from "@/Jetstream/Input";
import AInput from "@/A/AInput";
import AppLayout from "@/Layouts/AppLayout";
import SelectSupplier from "@/Shared/SelectSupplier";
import ATable from "@/A/ATable";
import InputError from "@/Jetstream/InputError";
import SelectGoldPercent from "@/A/SelectGoldPercent";
import SelectProductType from "@/A/SelectProductType";
import InputWeight from "@/A/InputWeight";
import Weight from "@/plugins/weight";
import AInputCurrency from "@/A/AInputCurrency";
import SelectProductDesign from "@/A/SelectProductDesign";

import useVuelidate from '@vuelidate/core';
import {required, requiredIf} from '@vuelidate/validators'

export default {
    setup() {
        return {v$: useVuelidate()}
    },
    metaInfo: {title: 'Create Suppliers'},
    components: {
        SelectProductDesign,
        AInputCurrency,
        InputWeight,
        SelectProductType,
        SelectGoldPercent,
        InputError,
        ATable,
        SelectSupplier,
        AInput,
        Input,
        JetFormSection,
        JetActionMessage,
        LoadingButton,
        SelectInput,
        TextInput,
    },
    layout: AppLayout,
    props: ['item', 'gold_percents', 'errors', 'goldprice'],
    data() {
        return {
            form: this.$inertia.form({
                id: this.item.id ?? null,
                dt: this.item.dt,
                sup_name: this.item.sup_name ?? null,
                product_weight_total: 0,
                cost_wage_total: 0,
                tag_wage_total: 0,
                cost_price_total: 0,
                tag_price_total: 0,
                cost_gold_total: 0,
                real_weight_total: 0,
                real_cost: 0,
                lines: this.item.lines,
            }),
            bill_goldprice: this.goldprice,
            creatingLine: false,
            formProduct: this.$inertia.form(),
            productChecked: false,
            product: {
                id: null,
                product_id: null,
                gold_percent: null,
                product_type_id: null,
                product_type: null,
                product_design_id: null,
                product_design: null,
                size: null,
                name: null,
                weight: 0,
                weightbaht: true,
                cost_wage: null,
                tag_wage: null,
                cost_price: null,
                tag_price: null,
                sale_with_gold_price: null,
                wage_by_pcs: null,

            },
            lines: [],
            line: {
                product_id: null,
                gold_percent: null,
                product_type_id: null,
                product_design_id: null,
                product_size: null,
                product_name: null,
                product_weight: null,
                product_min: null,
                qty: null,
                product_weight_total: null,
                avg_cost_per_baht: null,
                description: null,
                cost_wage: null,
                tag_wage: null,
                cost_price: null,
                tag_price: null,
                sale_with_gold_price: null,
            }
        }
    },
    validations() {
        return {
            line: {
                qty: {required},
                avg_cost_per_baht: {required},
                cost_wage: {
                    required: requiredIf(() => {
                        return this.product.sale_with_gold_price
                    })
                },
                tag_wage: {
                    required: requiredIf(() => {
                        return this.product.sale_with_gold_price
                    })
                },
                cost_price: {
                    required: requiredIf(() => {
                        return !this.product.sale_with_gold_price
                    })
                },
                tag_price: {
                    required: requiredIf(() => {
                        return !this.product.sale_with_gold_price
                    })
                }
            },
        }
    },
    watch: {
        product: {
            handler(val, oldVal) {
                if (this.productChecked) {
                    this.productChecked = false;
                    this.product.product_id = null;
                    this.product.id = null;
                }

                if (this.product.product_type)
                    this.product.product_type_id = this.product.product_type.id;
                if (this.product.product_design)
                    this.product.product_design_id = this.product.product_design.id;
            },
            deep: true
        },
        lines: {
            handler(val) {
                this.updateTotal();
            },
            deep: true
        }
    },
    computed: {
        product_weight_total() {
            let g = (this.product.weightbaht) ? this.product.weight * 15.2 : this.product.weight;
            return numeral(this.line.qty ?? 0).multiply(g).value()

        },
        productWeight: {
            get: () => {
                return 'ooooo'
                return {
                    weight: this.product.weight ?? null,
                    weightbaht: this.product.weightbaht ?? true
                }
            },
            set: (val) => {
                this.product.weight = val.weight;
                this.product.weightbaht = val.weightbaht;
            }
        },
    },
    methods: {
        createLine() {
            this.v$.$reset();
            this.product = _.mapValues(this.product, () => null);
            this.line = _.mapValues(this.product, () => null);
            this.product.weightbaht = true;
            this.product.sale_with_gold_price = true;
            this.product.wage_by_pcs = true;
            this.creatingLine = true;
        },
        checkProduct() {

            this.v$.$reset();
            this.v$.$touch();

            // let w = Weight(
            //     this.product.weight,
            //     this.product.weightbaht);
            //
            // let productId = '';
            // if (this.product.gold_percent)
            //     productId += this.product.gold_percent;
            // if (this.product.product_type_id)
            //     productId += this.product.product_type_id;
            // if (w.toGram())
            //     productId += w.toGram()
            // if (this.product.product_design_id)
            //     productId += 'D' + this.product.product_design_id
            //
            // if (this.product.size)
            //     productId += 'S' + this.product.size;


            let query = _.pickBy(this.product)

            this.$inertia.replace(route('stock-imports.create'), {
                checkProduct: true,
                ...query,
            }, {
                preserveState: true,
                preserveScroll: true,
                errorBag: 'lineBag',
                only: ['searchproduct', 'errors'],
                onSuccess: (page) => {
                    console.log('success');
                    console.log(page);
                    if (page.props.searchproduct) {
                        if (page.props.searchproduct.id) {
                            this.product.id = page.props.searchproduct.id;
                        } else {
                            this.product.id = null;
                        }
                        this.product.product_id = page.props.searchproduct.product_id;
                        this.product.name = page.props.searchproduct.name;
                    }
                    this.$nextTick(() => {
                        this.productChecked = true
                    })
                },
                onError: (errors) => {
                    console.log('errors')
                    console.log(errors);
                }
            })
        },
        storeLine() {
            let newline = _.assign({}, this.line, this.product)

            let w = Weight(
                this.product.weight,
                this.product.weightbaht);

            newline.cost_wage = this.line.cost_wage;
            newline.cost_price = this.line.cost_price;
            newline.product_weight_total = numeral(this.line.qty).multiply(w.toGram()).value();
            newline.cost_wage_total = numeral(this.line.qty).multiply(this.line.cost_wage ?? 0).value();
            newline.cost_price_total = numeral(this.line.qty).multiply(this.line.cost_price ?? 0).value();
            this.lines.push(newline);

        },
        updateTotal() {
            this.form.product_weight_total = 0;
            this.form.cost_wage_total = 0;
            this.form.cost_price_total = 0;
            this.form.product_qty_total = 0;

            _.each(this.lines, (line) => {
                console.log(line);
                this.form.product_weight_total += line.product_weight_total;
                this.form.cost_wage_total += line.cost_wage_total;
                this.form.cost_price_total += line.cost_price_total;
                this.form.product_qty_total += line.qty;
            })

            this.form.cost_gold_total = (this.bill_goldprice * .0656) * this.form.product_weight_total;

        },
        store() {
            this.form.lines = this.lines;

            this.form.post(route('stock-imports.store'), {
                errorBag: 'stockImportBag',
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset()
                    this.$emit('close');
                }
            })
        },
        formatNumber(val, pre = 0) {
            let format = '';
            switch (pre) {
                case 0:
                    format = '0,0';
                    break;
                case 2:
                    format = '0,0.00';
                    break;
            }
            return numeral(val).format(format);
        }
    }
}
</script>
