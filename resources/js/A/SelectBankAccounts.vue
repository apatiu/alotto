<template>
    <Dropdown
        :value="modelValue"
        @update:modelValue="$emit('update:modelValue',$event)"
        :options="options"
        optionValue="id"
        optionLabel="name">
        <template #option="slotProps">
            <div class="p-dropdown-car-option">
                <span>{{slotProps.option.acc_no}} {{slotProps.options.acc_name}}</span>
            </div>
        </template>
    </Dropdown>
</template>

<script>
export default {

    name: "SelectBankAccounts",
    props: ['modelValue'],
    data() {
        return {
            options: []
        }
    },
    mounted() {
        axios.get(route('api.bank-accounts.index'))
            .then(res => {
                this.options = res.data
            })
    }
}
</script>

<style scoped>

</style>
