<template>
    <div class="p-inputgroup">
        <AutoComplete
            v-model="selectedPawn"
            :suggestions="filteredPawns"
            placeholder="ค้นหาใบขายฝาก"
            force-selection
            field="code"
            @complete="searchPawn($event)"
            @itemSelect="onItemSelect"
        class="w-40">
            <template #item="{item, index}">
                <div class="flex">
                    <span class="w-20">{{ item.code }}</span>
                    <span class="w-48">{{ item.customer.name}}</span>
                    <div class="w-20 text-right">{{ $filters.decimal(item.price)}}</div>
                    <div class="w-20 text-center pl-4">
                        <pawn-status :model-value="item.status" small/></div>
                </div>
            </template>
        </AutoComplete>
        <Button label="รับขายฝาก" @click="createPawn"></Button>
        <form-pawn v-model:visible="visible" :pawn-id="pawnId"></form-pawn>
    </div>
</template>

<script>
import FormPawn from "@/Pages/Pawns/FormPawn";
import PawnStatus from "@/A/PawnStatus";

export default {
    name: "SmartbarPawn",
    components: {PawnStatus, FormPawn},
    data() {
        return {
            query: null,
            visible: false,
            pawnId: null,
            filteredPawns: null,
            selectedPawn: null,
        }
    },
    watch:{
        visible(val){
            if (!val) {
                this.selectedPawn = null
            }
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
        },
        onItemSelect(e) {
            this.pawnId = e.value.id
            this.visible = true
        }
    }
}
</script>

<style scoped>

</style>
