<template>
    <Dialog :visible="visible" header="เปิดกะ" :closable="false" modal>
        <div class="p-fluid">
            <div class="p-field p-formgrid">
                <label for="" class="p-col-fixed">เงินสดในลิ้นชัก</label>
                <div class="p-col">
                    <InputNumber v-model="form.cash_begin" ref="cashBegin" autofocus/>
                </div>
            </div>
            <div class="p-field">
                <Button @click="submit" label="ตกลง"/>
            </div>
        </div>
    </Dialog>
</template>

<script>
export default {
    name: "OpenShift",
    data() {
        return {
            visible: false,
            form: this.$inertia.form({
                d: new Date(),
                cash_begin: 0,
                cash_in: 0,
                cash_out: 0,
                cash_count: 0,
                cash_to_safe: 0,
                cash_to_bank: 0,
                cash_end: 0,
                bank_transfer_in: 0,
                bank_transfer_out: 0,
                bank_transfer_end: 0,
            })
        }
    },
    methods: {
        show() {
            this.visible = true;
            console.log(this.$refs.cashBegin);
        },
        submit() {
            this.form.post(route('shifts.store'), {
                onSuccess: (res) => {
                    this.visible = false
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
