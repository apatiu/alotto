<template>
    <div v-if="linkType==='dialog'">
        <Button :label="linkLabel" class="p-button-text p-button-sm" @click="open"></Button>
    </div>
    <inertia-link v-if="linkType==='link'" :href="linkUrl">{{ linkLabel }}</inertia-link>
    <form-pawn v-model:visible="pawnDialog" :pawn-id="data[fieldId]"></form-pawn>
    <form-saving v-model:visible="savingDialog" :saving-id="data[fieldId]"></form-saving>
</template>

<script>
import FormPawn from "@/Pages/Pawns/FormPawn";
import FormSaving from "@/Pages/Savings/FormSaving";

export default {
    name: "CellRef",
    components: {FormSaving, FormPawn},
    props: {
        data: Object,
        morphName: {default: 'ref'},
        fieldId: {default: 'ref_id'},
        fieldType: {default: 'ref_type'},
        fieldLabel: {default: 'code'}
    },
    data() {
        return {
            linkType: '',
            linkLabel: '',
            linkUrl: '',
            pawnDialog: false,
            savingDialog: false,
        }
    },
    mounted() {
        switch (this.data[this.fieldType]) {
            case 'App\\Models\\Pawn':
                this.linkType = 'dialog';
                this.linkLabel = this.data[this.morphName][this.fieldLabel];
                break;
            case 'App\\Models\\StockImport':
                this.linkType = 'link';
                this.linkLabel = this.data[this.morphName][this.fieldLabel];
                this.linkUrl = route('stock-imports.edit', this.data[this.morphName]['id'])
                break;
            case 'App\\Models\\Saving':
                this.linkType = 'dialog';
                this.linkLabel = this.data[this.morphName][this.fieldLabel];
                break;
        }
    },
    methods: {
        open() {
            switch (this.data[this.fieldType]) {
                case 'App\\Models\\Pawn':
                    this.pawnDialog = true;
                    break;
                case 'App\\Models\\Saving':
                    this.savingDialog = true;
                    break;
            }
        }
    }
}
</script>

<style scoped>

</style>
