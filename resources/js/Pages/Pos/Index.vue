<template>
    <app-layout>
        <pos :bill="bill"></pos>
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
    },
    data() {
        return {}
    }
}
</script>
