<template>
    <AppLayout>
        <div :class="['paper',paperSize,paperOrientation]">
            {{ data }}
        </div>
    </AppLayout>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "Print",
    components: {AppLayout},
    props: {
        'data': {default: null},
        'paperSize': {default: 'A4'},
        paperOrientation: {default: 'portait'}
    },
    mounted() {
        switch (this.paperSize) {
            case 'A4':

        }
    }
}
</script>

<style scoped>
.paper {
    background: white;
}
</style>
