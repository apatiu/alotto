<template>
    <AppLayoutCenterScreen>
        <form @submit.prevent="onSubmit">
            <h5>เปิดกะทำงาน สาขา: {{ shift.team.name }}</h5>
            <div class="p-fluid">
                <div class="p-field">
                    <label>เงินสดเริ่มกะ</label>
                    <InputBaht v-model="form.cash_begin"/>
                </div>
            </div>
            <ButtonSave type="submit"/>
        </form>
    </AppLayoutCenterScreen>

</template>

<script>
import AppLayoutCenterScreen from "../../Layouts/AppLayoutCenterScreen";
import InputBaht from "../../A/InputBaht";
import ButtonSave from "../../A/ButtonSave";

export default {
    name: "Create",
    components: {ButtonSave, InputBaht, AppLayoutCenterScreen},
    props: {shift: Object},
    data() {
        return {
            form: this.$inertia.form(this.shift)
        }
    },
    methods:{
        onSubmit() {
            this.form.post(route('shifts.store'))
        }
    }
}
</script>

<style scoped>

</style>
