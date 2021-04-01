<template>
    <Dropdown
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue',$event)"
        v-model="selected"
        :options="options"
        optionValue="id"
        optionLabel="name">
        <template #option="slotProps">
            <div>
                <span>{{ slotProps.option.name }} {{ slotProps.option.acc_no }}</span>
            </div>
        </template>
    </Dropdown>
</template>

<script>
export default {

    name: "SelectBankAccount",
    props: ['modelValue'],
    data() {
        return {
            options: [],
            selected: null
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
