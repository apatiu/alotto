<template>
    <app-layout>
        <pos :bill="bill" :customer="customer"></pos>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Pos from "@/Pages/Pos";

export default {
    components: {
        Pos,
        AppLayout,
    },
    props: {
        bill: Object,
        customer: Object,
    },
    data() {
        return {}
    }
}
</script>
