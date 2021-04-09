<template>
    <div v-if="linkType==='dialog'">
        <Button :label="linkLabel" class="p-button-text p-button-sm" @click="open"></Button>
    </div>
    <inertia-link v-if="linkType==='link'" :href="link.url">{{ link.label }}</inertia-link>
    <form-pawn v-model:visible="pawnDialog" :pawn-id="data.ref_id"></form-pawn>
</template>

<script>
import FormPawn from "@/Pages/Pawns/FormPawn";

export default {
    name: "CellRef",
    components: {FormPawn},
    props: ['data'],
    data() {
        return {
            linkType: '',
            linkLabel: '',
            linkUrl: '',
            pawnDialog: false
        }
    },
    mounted() {
        switch (this.data.ref_type) {
            case 'App\\Models\\Pawn':
                this.linkType = 'dialog';
                this.linkLabel = this.data.ref.code;
                break;
        }
    },
    methods: {
        open() {
            switch (this.data.ref_type) {
                case 'App\\Models\\Pawn':
                    this.pawnDialog = true;
                    break;
            }
        }
    }
}
</script>

<style scoped>

</style>
