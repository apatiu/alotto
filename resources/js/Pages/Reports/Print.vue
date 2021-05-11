<template>
    <AppLayout>
        <Toolbar>
            <template #right>
                <Calendar v-model="dBegin"></Calendar>
                <Button icon="pi pi-print" class="ml-2"></Button>
            </template>
        </Toolbar>
        <div :class="['page A4',paperSize,paperOrientation]">
            <div class="p-grid">
                <div class="p-col">
                    <DataTable :value="data.payments" class="p-datatable-sm">
                        <Column field="payment_type.name" header="รายการ"></Column>
                        <Column field="total_receive" header="รับ" class="text-right">
                            <template #footer>
                                {{ $filters.decimal( totalReceive )}}
                            </template>
                        </Column>
                        <Column field="total_pay" header="จ่าย" class="text-right">
                            <template #footer>
                                {{ $filters.decimal( totalPay )}}
                            </template>
                        </Column>
                        <template #groupheader="slotProps">
                            <span class="image-text">{{ slotProps.data.payment_type.name }}</span>
                        </template>
                    </DataTable>
                </div>
                <div class="p-col"></div>
            </div>

        </div>
    </AppLayout>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "Print",
    components: {AppLayout},
    props: {
        'data': {default: null},
        'dBegin': {default: new Date()}
    },
    data() {
        return {
            form: this.$inertia.form({
                dBegin: this.dBegin
            }),
        }
    },
    mounted() {
    },
    computed: {
        totalReceive() {
            return _.sumBy(this.data.payments, (o) => {
                return numeral(o.total_receive).value()
            })
        },
        totalPay() {
            return _.sumBy(this.data.payments, (o) => {
                return numeral(o.total_pay).value()
            })
        }
    }
}
</script>

<style scoped>
</style>
