<template>
    <div class="py-4 px-6">
        <h1 class="text-2xl mb-6">สร้างใบรับสินค้า</h1>
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
                <Calendar v-model="form.d" dateFormat="dd-mm-yy" class="w-full"></Calendar>
                <input-error :message="form.errors.d"></input-error>
            </div>
            <div class="col-start-6">
                <select-supplier
                    v-model="form.sup_name"
                    label="ผู้จำหน่าย"
                    :error="form.errors.sup_name">
                </select-supplier>
            </div>
        </div>
        <div class="flex p-mt-3 justify-end">
            <Button @click="createLine">เพิ่มสินค้า</Button>
        </div>
        <DataTable :value="lines">
            <Column field="name" header="ชื่อสินค้า"></Column>
        </DataTable>

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
    <Dialog header="เพิ่มสินค้า" v-model:visible="creatingLine" :modal="true" :style="{width:'80vw'}">
        <div class="p-fluid p-grid p-pt-4">
            <div class="p-col-9">
                <div class="p-grid">
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
                    </div>
                    <div class="p-field p-col-3">
                        <select-product-type v-model="product.product_type"></select-product-type>
                    </div>
                    <div class="p-field p-col-3">
                        <input-weight :model-value="[product.weight,product.weightbaht]"
                                      @update:model-value="updateProductWeight"></input-weight>
                    </div>
                    <div class="p-field p-col-9">
                        <a-input
                            v-model="product.product_design"
                            label="ลาย"></a-input>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input
                            v-model="product.size"
                            label="ขนาด"></a-input>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input v-model="product.line_qty" label="จำนวน"></a-input>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input v-model="product_weight_total"
                                 label="น้ำหนักรวม"
                                 disabled></a-input>
                    </div>
                    <div class="p-field p-col-3">
                        <a-input-currency v-model="product.avg_cost_per_baht" label="ต้นทุน/บาท"></a-input-currency>
                    </div>
                </div>
                <div class="p-grid">
                    <div class="p-col-4 p-flex-column">
                        <div class="p-field-radiobutton">
                            <RadioButton v-model="product.sale_with_gold_price"
                                         name="sale_with_gold"
                                         :value="true"></RadioButton>
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

                        <div class="p-field mt-8">
                                <span class="p-float-label">
                                    <InputNumber v-model="product.cost_wage"
                                                 :disabled="product.sale_with_gold_price === false"/>
                                    <label>ค่าแรงทุน</label>
                                </span>
                        </div>
                        <div class="p-field mt-8">
                                <span class="p-float-label">
                                    <InputNumber v-model="product.tag_wage"
                                                 :disabled="product.sale_with_gold_price === false"/>
                                    <label>ค่าแรงขาย</label>
                                </span>
                        </div>
                    </div>
                    <div class="p-col-4 p-offset-2 p-flex-column justify-end">
                        <div class="p-field-radiobutton">
                            <RadioButton v-model="product.sale_with_gold_price"
                                         name="sale_with_gold"
                                         :value="false"></RadioButton>
                            <label>ราคาคงที่</label>
                        </div>
                        <div class="p-field mt-8">
                                <span class="p-float-label">
                                    <InputNumber v-model="product.cost_price"
                                                 :disabled="product.sale_with_gold_price === true"/>
                                    <label>ราคาทุน</label>
                                </span>
                        </div>
                        <div class="p-field mt-8">
                                <span class="p-float-label">
                                    <InputNumber v-model="product.tag_price"
                                                 :disabled="product.sale_with_gold_price === true"/>
                                    <label>ราคาขาย</label>
                                </span>
                        </div>
                    </div>

                </div>
                <div class="p-grid">
                    <div class="p-col-12 mt-8">
                        <a-input v-model="product.description" label="รายละเอียด"></a-input>
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
            <div class="flex w-full justify-between">
                <div>
                    <Button label="ตรวจสอบ" class="p-button-success" @click="checkProduct"></Button>
                </div>
                <div>
                    <Button label="ยกเลิก" icon="pi pi-times"
                            class="p-button-text"
                            @click="creatingLine=false"/>
                    <Button label="บันทึก" icon="pi pi-check" autofocus/>
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

export default {
    metaInfo: {title: 'Create Suppliers'},
    components: {
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
    props: ['item', 'gold_percents'],
    data() {
        return {
            form: this.$inertia.form({
                id: this.item.id ?? null,
                d: this.item.d,
                sup_name: this.item.sup_name ?? null,
                lines: this.item.lines,
            }),
            creatingLine: false,
            errors: true,
            product: {
                product_id: null,
                gold_percent: null,
                product_type_id: null,
                product_type: null,
                product_design_id: null,
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
                line_qty: null,
                line_product_weight_total: null,
                line_avg_cost_per_baht: null,
                line_description: null
            }
        }
    },
    watch: {
        product: {
            handler(val) {
                if (this.product.product_type)
                    this.product.product_type_id = this.product.product_type.id;
            },
            deep: true
        }
    },
    computed: {
        product_weight_total() {
            let w = Weight(
                this.product.weight,
                this.product.weightbaht);
            return this.product.line_qty * w.toGram();

        }
    },
    methods: {
        createLine() {
            this.product = _.mapValues(this.product, () => null);
            this.product.weightbaht = true;
            this.product.sale_with_gold_price = true;
            this.product.wage_by_pcs = true;
            this.creatingLine = true;
        },
        updateProductWeight(event) {
            this.product.weight = event[0]
            this.product.weightbaht = event[1]
        },
        checkProduct() {
            let w = Weight(
                this.product.weight,
                this.product.weightbaht);

            let productId = '';
            if (this.product.gold_percent)
                productId += this.product.gold_percent;
            if (this.product.product_type_id)
                productId += this.product.product_type_id;
            if (w.toGram())
                productId += w.toGram()
            if (this.product.product_design_id)
                productId += 'D' + this.product.product_design_id

            if (this.product.size)
                productId += 'S' + this.product.size;

            this.$inertia.get(route('stock-imports.index'), this.product, {
                preserveScroll: true,
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: (errors) => {
                    console.log(errors);
                }
            })
        },
        store() {
            this.form.post(route('stock-imports.store'), {
                errorBag: 'stockImportBag',
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset()
                    this.$emit('close');
                }
            })
        }
    }
}
</script>
