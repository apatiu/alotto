<template>
    <div class="py-4 px-6">
        <div class="p-grid">
            <div class="p-col"><h1 class="text-2xl mb-6">ใบรับสินค้า</h1></div>
            <div class="p-col p-text-right">
                <stock-import-status v-model:value="form.status"></stock-import-status>
            </div>
        </div>
        <div class="grid grid-cols-6 gap-2">
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
                <Calendar v-model="v.form.dt.$model"
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
            <Column field="qty" header="จำนวน">
                <template #footer>{{ formatNumber(form.product_qty_total) }}</template>
            </Column>
            <Column field="product_weight" header="น้ำหนัก/ชิ้น">
                <template #body="slotProps">
                    {{
                        slotProps.data.product_weightbaht ? (slotProps.data.product_weight * 15.2).toFixed(2) :
                            slotProps.data.product_weight.toFixed(2)
                    }}
                </template>
            </Column>
            <Column field="cost_wage" header="ค่าแรง/ชิ้น"></Column>
            <Column field="cost_wage_total" header="รวมค่าแรง">
                <template #footer>{{ formatNumber(form.cost_wage_total) }}</template>
            </Column>
            <Column field="cost_price" header="ราคาทุน/ชิ้น"></Column>
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
            <template #empty>
                <div class="w-full text-center py-8">ยังไม่มีรายการสินค้า</div>
            </template>
        </DataTable>

        <div class="flex justify-end mt-6">
            <div class="grid grid-cols-5 grid-rows-3 gap-4">
                <div class="row-span-2">
                    <label for="">บันทึกย่อ</label>
                    <Textarea v-model="form.note"></Textarea>
                </div>
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
                    <input-error :model-value="v.form.real_weight_total.$errors"></input-error>
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
                    <input-error :model-value="v.form.real_cost.$errors"></input-error>
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
        <div class="pt-4 flex justify-end">
            <Button @click="$inertia.visit('/stock-imports')" class="p-mr-2 p-button-text">ยกเลิก</Button>
            <Button :class="{ 'opacity-25': form.processing }"
                    :disabled="!saveable"
                    @click="save(false)">
                บันทึก
            </Button>
            <Button class="p-ml-2"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="!saveable"
                    @click="save(true)">
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
import {required, requiredIf, helpers} from '@vuelidate/validators'
import StockImportStatus from "@/A/StockImportStatus";
import CreateStockImportLine from "@/Pages/StockImports/CreateStockImportLine";

export default {
    setup() {
        return {v: useVuelidate()}
    },
    metaInfo: {title: 'Edit Suppliers'},
    created() {
        if (this.item.code) {
            _.assign(this.form, this.item);
            this.modeEdit = true
        }

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
            modeEdit: false,
            form: this.$inertia.form({
                id: null,
                code: null,
                dt: new Date(),
                team_id: null,
                supplier_id: null,
                real_cost: null,
                real_weight_total: null,
                cost_price_total: null,
                note: null,
                cost_gold_total: null,
                cost_wage_total: null,
                tag_price_total: null,
                product_weight_total: null,
                emp_name: null,
                tag_wage_total: null,
                status: null,
                product_qty_total: null,
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
                real_weight_total: {required},
            },
        }
    },
    watch: {
        'form.lines': {
            handler() {
                this.updateTotal()
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
            return this.form.status === 'approved';
        },
        saveable() {
            return !this.form.processing
                && !this.isApproved
                && this.form.lines.length > 0
                && !this.v.form.$invalid
        }
    },
    methods: {
        createLine() {
            this.$refs.createLineDialog.show();
        },
        addLine(e) {
            this.form.lines.push(_.pickBy(e));
        },
        removeLine(props) {
            this.form.lines.splice(props.index, 1)
        },
        updateLineTotal(newline) {
            let w = Weight(
                newline.product_weight ?? 0,
                newline.product_weightbaht);
            newline.cost_wage_total = numeral(newline.qty).multiply(newline.cost_wage ?? 0).value();
            newline.product_weight_total = numeral(newline.qty).multiply(w.toGram()).value();
            newline.cost_price_total = numeral(newline.qty).multiply(newline.cost_price ?? 0).value();
        },
        updateTotal() {
            this.form.product_weight_total = 0;
            this.form.cost_wage_total = 0;
            this.form.cost_price_total = 0;
            this.form.product_qty_total = 0;
            this.form.cost_gold_total = 0;

            _.each(this.form.lines, (line) => {
                this.form.product_weight_total = numeral(line.product_weight_total).add(this.form.product_weight_total).value();
                this.form.cost_wage_total += line.cost_wage_total;
                this.form.cost_price_total += line.cost_price_total;
                this.form.cost_gold_total += line.cost_gold_total;
                this.form.product_qty_total += line.qty;
            })
        },
        save(approve = false) {
            this.v.form.$touch();
            if (this.v.form.$error) return

            if (approve) {
                this.$confirm.require({
                    message: 'อนุมัติใบรับสินค้า และตัดสต้อก หรือไม่?',
                    header: 'กรุณายืนยัน',
                    icon: 'pi pi-exclamation-triangle',
                    accept: () => {
                        this.form.status = 'approve-request'
                        this.post()
                    },
                    reject: () => {
                        return
                    }
                });
            } else this.post()
        },
        post() {
            if (this.form.id) {
                this.form.put(route('stock-imports.update', this.form.id), {
                    errorBag: 'stockImportBag',
                    preserveScroll: true,
                    onSuccess: (res) => {
                        this.$toast.add({severity: 'success', summary: 'บันทึกข้อมูลแล้ว', life: 3000})
                        // _.assign(this.form, res.props.item);
                    }
                })
            } else {
                this.form.post(route('stock-imports.store'), {
                    errorBag: 'stockImportBag',
                    preserveScroll: true,
                    onSuccess: (res) => {
                        this.notify('บันทึกข้อมูลแล้ว')
                    }
                })
            }
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
