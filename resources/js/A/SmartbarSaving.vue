<template>
    <div class="p-inputgroup">
        <AutoComplete
            v-model="selectedSaving"
            :suggestions="filteredSavings"
            placeholder="ใบออมทอง"
            force-selection
            field="code"
            @complete="search($event)"
            @itemSelect="onItemSelect"
            class="w-28">
            <template #item="{item, index}">
                <div class="flex">
                    <span class="w-20">{{ item.code }}</span>
                    <span class="w-48">{{ item.customer.name }}</span>
                    <div class="w-20 text-right">{{ $filters.decimal(item.price_total) }}</div>
                    <div class="w-20 text-center pl-4">
                        <saving-status :model-value="item.status" small/>
                    </div>
                </div>
            </template>
        </AutoComplete>
        <Button label="เปิดออม" @click="create" class="p-button-success"></Button>
        <form-saving v-model:visible="visible" :saving-id="savingId"></form-saving>
    </div>
</template>

<script>
import FormSaving from "@/Pages/Savings/FormSaving";
import SavingStatus from "@/A/SavingStatus";

export default {
    name: "SmartbarSaving",
    components: {SavingStatus, FormSaving},
    data() {
        return {
            query: null,
            visible: false,
            savingId: null,
            filteredSavings: null,
            selectedSaving: null,
        }
    },
    methods: {
        create() {
            this.savingId = null;
            this.visible = true;
        },
        search(e) {
            console.log('Searching saving.')
            axios.get(route('api.savings.search'), {
                params: {q: e.query}
            })
                .then(({data}) => {
                    this.filteredSavings = data.data
                })
        },
        onItemSelect(e) {
            console.log('Item selected')
            this.savingId = e.value.id
            this.visible = true
        }
    }
}
</script>

<style scoped>

</style>
