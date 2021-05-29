<template>
    <app-layout>
        <gold-prices></gold-prices>
<!--        <welcome></welcome>-->
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Sidebar from "@/Shared/Sidebar";
import Pos from "@/Pages/Pos";
import GoldPrices from "@/A/GoldPrices";

export default {
    components: {
        GoldPrices,
        Pos,
        Sidebar,
        AppLayout,
        Welcome,
    },
    data() {
        return {}
    }
}
</script>
