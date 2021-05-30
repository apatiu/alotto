<template>
    <SelectCustomer v-model="form.customer" @update:modelValue="form.customer_id=$event.id"/>
    <p>customer:</p>
    <div>{{ form.customer }}</div>
    <p>customer_id: {{ form.customer_id }}</p>
</template>

<script>
import SelectCustomer from "@/A/SelectCustomer";

export default {
    name: "Test",
    components: {SelectCustomer},
    data() {
        return {
            form: this.$inertia.form({
                customer: null,
                customer_id: null,
            })
        }
    }
}
</script>

<style scoped>

</style>
