<template>
    <div class="p-inputgroup">
        <AutoComplete
            v-model="selectedPawn"
            :suggestions="filteredPawns"
            placeholder="ค้นหาใบขายฝาก"
            force-selection
            field="code"
            @complete="searchPawn($event)"></AutoComplete>
        <Button label="รับขายฝาก" @click="createPawn"></Button>
        <form-pawn v-model:visible="visible" :pawn-id="pawnId"></form-pawn>
    </div>
</template>

<script>
import FormPawn from "@/Pages/Pawns/FormPawn";

export default {
    name: "SmartbarPawn",
    components: {FormPawn},
    data() {
        return {
            query: null,
            visible: false,
            pawnId: null,
            filteredPawns: null
        }
    },
    methods: {
        createPawn() {
            this.pawnId = null;
            this.visible = true;
        },
        searchPawn(e) {
            axios.get(route('api.pawns.search'), {
                params: {q: e.query}
            })
                .then(({data}) => {
                    this.filteredPawns = data.data
                })
        }
    }
}
</script>

<style scoped>

</style>
