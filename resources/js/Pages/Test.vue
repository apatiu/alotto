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
                customer: {
                    "id": 2,
                    "initial": null,
                    "name": "aaa",
                    "team_id": null,
                    "contact_person": null,
                    "birthday": null,
                    "tax_id": null,
                    "address": null,
                    "city": null,
                    "district": null,
                    "province": null,
                    "country": null,
                    "postal_code": null,
                    "idcard_start": null,
                    "idcard_end": null,
                    "idcard_place": null,
                    "email": null,
                    "phone": null,
                    "created_at": "2021-05-30T08:37:38.000000Z",
                    "updated_at": "2021-05-30T08:37:38.000000Z",
                    "id_label": "C00002"
                },
                customer_id: null,
            })
        }
    }
}
</script>

<style scoped>

</style>
