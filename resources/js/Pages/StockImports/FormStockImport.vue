<template>
    <div class="py-4 px-6">
        <div class="p-grid">
            <div class="p-col"><h1 class="text-2xl mb-6">ใบรับสินค้า</h1></div>
            <div class="p-col p-text-right">
                <stock-import-status v-model:value="form.status"></stock-import-status>
            </div>
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
                <Calendar v-model="v$.form.dt.$model"
                          class="w-full"
                ></Calendar>
                <input-error :message="form.errors.dt"></input-error>
            </div>
            <div class="col-start-6">
                <select-supplier
                    v-model="form.supplier_id"
                    label="ผู้จำหน่าย"
                    :error="form.errors.supplier_id">
                </select-supplier>
            </div>
        </div>
        <div class="flex p-mt-3 justify-end">
            <Button @click="createLine">เพิ่มสินค้า</Button>
        </div>
        <DataTable :value="lines">
            <Column field="product_id" header="รหัสสินค้า"></Column>
            <Column field="product_name" header="ชื่อสินค้า"></Column>
            <Column field="product_qty" header="จำนวน">
                <template #footer>{{ formatNumber(form.product_qty_total) }}</template>
            </Column>
            <Column field="product_weight" header="น้ำหนักต่อชิ้น">
                <template #body="slotProps">
                    {{
                        slotProps.data.product_weightbaht ? (slotProps.data.product_weight * 15.2).toFixed(2) :
                            slotProps.data.product_weight.toFixed(2)
                    }}
                </template>
            </Column>
            <Column field="cost_wage" header="ค่าแรงทุน"></Column>
            <Column field="cost_wage_total" header="รวมค่าแรงทุน">
                <template #footer>{{ formatNumber(form.cost_wage_total) }}</template>
            </Column>
            <Column field="cost_price" header="ราคาทุน"></Column>
            <Column field="cost_price_total" header="รวมทุน">
                <template #footer>{{ formatNumber(form.cost_price_total) }}</template>
            </Column>
            <Column field="product_weight_total" header="รวมน้ำหนัก">
                <template #footer>{{ formatNumber(form.product_weight_total, 2) }}</template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button icon="pi pi-trash" class="p-button-rounded p-button-text"
                            @click="removeLine(slotProps)"/>
                </template>
            </Column>
        </DataTable>

        <div class="flex justify-end mt-6">
            <div class="grid grid-cols-4 grid-rows-3 gap-4">
                <div class="flex items-center justify-end auto-cols-max">
                    น้ำหนักชั่งจริง
                </div>
                <div>
                    <InputNumber
                        v-model="form.real_weight_total"
                        mode="decimal"
                        input-class="text-right"
                        :minFractionDigits="2"
                        :maxFractionDigits="2"></InputNumber>
                </div>
                <div class="flex items-center justify-end">
                    ราคาทอง
                </div>
                <div>
                    <InputNumber v-model="form.cost_gold_total"
                                 class="w-full"
                                 input-class="text-right"
                                 :maxFractionDigits="2" disabled></InputNumber>
                </div>
                <div class="flex items-center justify-end">ยอดจ่ายจริง</div>
                <div>
                    <InputNumber v-model="form.real_cost"
                                 mode="decimal"
                                 :minFractionDigits="2"
                                 :maxFractionDigits="2"
                                 inputClass="text-right"
                                 class="w-full"></InputNumber>
                </div>
                <div class="flex items-center justify-end">ค่าแรง</div>
                <div>
                    <InputNumber v-model="form.cost_wage_total"
                                 class="w-full"
                                 input-class="text-right"
                                 :maxFractionDigits="2"
                                 disabled></InputNumber>
                </div>
            </div>
        </div>

        <div class="text-right" v-if="form.errors">
            <p v-for="item in form.errors" class="p-error">{{ item }}</p>
        </div>
        <div class="pt-4 flex justify-end">
            <Button @click="$inertia.visit('/stock-imports')" class="p-mr-2 p-button-text">ยกเลิก</Button>
            <Button :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="update">
                บันทึกร่าง
            </Button>
            <Button class="p-ml-2"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing || this.form.status==='approved'"
                    @click="approveClick">
                อนุมัติ
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
                        <input-weight :model-value="{weight:  v$.product.weight.$model, weightbaht: product.weightbaht}"
                                      @update:model-value="updateProductWeight"></input-weight>
                        <input-error class="p-error"
                                     :errors="v$.product.weight.$errors"></input-error>
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
import StockImportStatus from "@/A/StockImportStatus";

export default {
    setup() {
        return {v$: useVuelidate()}
    },
    metaInfo: {title: 'Edit Suppliers'},
    created() {
        this.form.dt = moment(this.form.dt).toDate();
    },
    mounted() {
        if (this.form.id) this.updateTotal();
    },
    components: {
        StockImportStatus,
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
            form: this.$inertia.form(this.item),
            approve: false,
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
                weight: null,
                weightbaht: null,
                cost_wage: null,
                tag_wage: null,
                cost_price: null,
                tag_price: null,
                sale_with_gold_price: null,
                wage_by_pcs: null,

            },
            lines: this.item.lines ?? [],
            line: {
                product_id: null,
                gold_percent: null,
                product_type_id: null,
                product_design_id: null,
                product_size: null,
                product_name: null,
                product_weight: null,
                product_weightbaht: null,
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
            product: {
                weight: {
                    required: requiredIf(() => {
                        return this.product.sale_with_gold_price
                    })
                }
            },
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
            form: {
                dt: {required},
                real_cost: {required},
            }
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
            let w = this.product.weight;
            if (this.product.weightbaht)
                w = w * 15.2;
            return numeral((this.line.qty ?? 0) * w).value().toFixed(2)
        },
        isApproved() {
            return this.form.status;
        }
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
        updateProductWeight(event) {
            this.product.weight = event.weight
            this.product.weightbaht = event.weightbaht
        },
        checkProduct() {

            this.v$.$reset();
            this.v$.$touch();

            let query = _.pickBy(this.product)
            let url = null;
            if (this.form.id) {
                url = route(route().current(), this.form.id)
            } else {
                url = route(route().current())
            }

            this.$inertia.get(url, {
                ...query,
                checkProduct: true
            }, {
                preserveState: true,
                preserveScroll: true,
                errorBag: 'lineBag',
                only: ['searchproduct', 'errors'],
                onSuccess: (page) => {
                    console.log('success');
                    if (page.props.searchproduct) {
                        if (page.props.searchproduct.id) {
                            this.product.id = page.props.searchproduct.id;
                        } else {
                            this.product.id = null;
                        }
                        this.product.product_id = page.props.searchproduct.product_id;
                        this.product.name = page.props.searchproduct.name;
                        this.product.created_at = null;
                        this.product.updated_at = null;

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
            let w = Weight(
                this.product.weight ?? 0,
                this.product.weightbaht);

            let newline = _.assign({}, this.line, this.product)

            newline.id = null;
            newline.product_name = newline.name;
            newline.product_qty = newline.qty;
            newline.product_weight = newline.weight;
            newline.product_weightbaht = newline.weightbaht;

            newline.cost_wage = this.line.cost_wage;
            newline.cost_price = this.line.cost_price;

            newline.name = null;
            newline.qty = null;
            newline.weight = null;
            newline.updated_at = null;
            newline.created_at = null;

            this.updateLineTotal(newline);

            this.lines.push(_.pickBy(newline));

        },
        removeLine(props) {
            this.lines.splice(props.index, 1)
        },
        updateLineTotal(newline) {
            let w = Weight(
                newline.product_weight ?? 0,
                newline.product_weightbaht);
            newline.cost_wage_total = numeral(newline.product_qty).multiply(newline.cost_wage ?? 0).value();
            newline.product_weight_total = numeral(newline.product_qty).multiply(w.toGram()).value();
            newline.cost_price_total = numeral(newline.product_qty).multiply(newline.cost_price ?? 0).value();
        },
        updateTotal() {
            this.form.product_weight_total = 0;
            this.form.cost_wage_total = 0;
            this.form.cost_price_total = 0;
            this.form.product_qty_total = 0;
            this.form.cost_gold_total = 0;

            _.each(this.lines, (line) => {
                // this.updateLineTotal(line);
                this.form.product_weight_total = numeral(line.product_weight_total).add(this.form.product_weight_total).value();
                this.form.cost_wage_total += line.cost_wage_total;
                this.form.cost_price_total += line.cost_price_total;

                this.form.cost_gold_total =
                    numeral(this.form.cost_gold_total).value() +
                    numeral(line.avg_cost_per_baht)
                        .divide(15.2)
                        .multiply(this.form.product_weight_total)
                        .value();

                this.form.product_qty_total += line.qty;
            })
        },
        update() {
            this.form.lines = this.lines;

            if (this.form.id) {
                this.form.put(route('stock-imports.update', this.form.id), {
                    errorBag: 'stockImportBag',
                    preserveScroll: true,
                    onSuccess: (res) => {
                        this.$toast.add({severity: 'success', summary: 'บันทึกข้อมูลแล้ว', life: 3000})
                        _.assign(this.form, res.props.item);
                    }
                })
            } else {
                this.form.post(route('stock-imports.store'), {
                    errorBag: 'stockImportBag',
                    preserveScroll: true,
                    onSuccess: (res) => {
                        console.log(res)
                        _.assign(this.form, res.props.item);
                    }
                })
            }
        },
        approveClick() {
            this.$confirm.require({
                message: 'อนุมัติใบรับสินค้า และตัดสต้อก หรือไม่?',
                header: 'กรุณายืนยัน',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    this.form.status = 'approve-request'
                    this.update();
                },
                reject: () => {
                }
            });


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
