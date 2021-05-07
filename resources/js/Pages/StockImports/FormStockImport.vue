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
                    v-model="form.code"
                    :error="form.errors.code"
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
        <DataTable :value="form.lines">
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
            <Column field="tag_wage" header="ค่าแรงขาย"></Column>
            <Column field="cost_price" header="ราคาทุน"></Column>
            <Column field="cost_price_total" header="รวมทุน">
                <template #footer>{{ formatNumber(form.cost_price_total) }}</template>
            </Column>
            <Column field="product_weight_total" header="รวมน้ำหนัก">
                <template #footer>{{ formatNumber(form.product_weight_total, 2) }}</template>
            </Column>
            <Column class="w-14 p-0">
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
    <create-stock-import-line ref="createLineDialog"
    @update:modelValue="addLine"></create-stock-import-line>
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
import CreateStockImportLine from "@/Pages/StockImports/CreateStockImportLine";

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
        CreateStockImportLine,
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
            form: this.$inertia.form({
                id: null,
                code: null,
                dt: new Date(),
                lines: []
            }),
            approve: false,
            bill_goldprice: this.goldprice,
            creatingLine: false,

        }
    },
    validations() {
        return {
            form: {
                dt: {required},
                real_cost: {required},
            }
        }
    },
    watch: {
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
            this.$refs.createLineDialog.show();
        },
        addLine(e) {

            this.updateLineTotal(e);

            this.form.lines.push(_.pickBy(e));

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
