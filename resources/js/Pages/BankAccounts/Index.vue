<template>
    <AppLayout>
    <template #header><h3>บัญชีธนาคาร</h3></template>
        <BankAccounts></BankAccounts>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import BankAccounts from "@/Pages/BankAccounts/BankAccounts";
export default {
    name: "Index",
    components: {AppLayout,BankAccounts}
}
</script>

<style scoped>

</style>
