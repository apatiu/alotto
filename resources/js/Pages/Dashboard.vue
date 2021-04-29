<template>
    <app-layout>
        <pos></pos>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Sidebar from "@/Shared/Sidebar";
import Pos from "@/Pages/Pos";

export default {
    components: {
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
