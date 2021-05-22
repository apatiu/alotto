<template>
    <Dropdown
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue',$event)"
        :options="groupedTypes"
        optionGroupLabel="label"
        optionGroupChildren="items"
        optionLabel="name"
        placeholder="ประเภท"
        class="w-36"
        scrollHeight="400px"
        :class="classObject">
        <template #optiongroup="slotProps">
            <div class="p-d-flex p-ai-center country-item">
                <i :class="slotProps.option.icon"></i>
                <div class="ml-2">{{ slotProps.option.label }}</div>
            </div>
        </template>
        <template #option="{option}">
            <div class="pl-8">{{ option.name }}</div>
        </template>
    </Dropdown>
</template>

<script>
import {ref} from "vue";

export default {
    name: "SelectPaymentType",
    setup(props) {
        const groupedTypes = ref([])
        const getGroupedTypes = async () => {
            groupedTypes.value = await axios.get(route('api.payment-types.index'))
                .then(({data}) => {
                    let grouped = [{
                        label: 'รายรับ',
                        icon: 'pi pi-plus-circle text-green-700',
                        items: []
                    }, {
                        label: 'รายจ่าย',
                        icon: 'pi pi-minus-circle text-red-700',
                        items: []
                    }]
                    _.each(data, (item) => {
                        console.log(item.refable + ' : ' + props.refable)
                        if (item.refable == props.refable)
                            (item.type === 'pay') ? grouped[1].items.push(item) : grouped[0].items.push(item)
                    })
                    return grouped
                })
        }
        return {groupedTypes, getGroupedTypes}
    },
    props: {
        'modelValue': {default: null}, refable: {default: false},
        error: {type: Boolean, default: false}
    },
    computed: {
        classObject() {
            return {
                'p-invalid': this.error
            }
        }
    },
    mounted() {
        this.getGroupedTypes()
    }
}
</script>

<style scoped>

</style>
