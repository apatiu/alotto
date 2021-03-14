<template>

    <div class="sm:p-4">
        <h1 class="mb-8 font-bold text-3xl">สินค้า</h1>

        <panel>
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">ID</th>
                    <th class="px-6 pt-6 pb-4">Name</th>
                </tr>
                <tr v-for="item in items" :key="item.id"
                    class="cursor-pointer hover:bg-gray-100 focus-within:bg-gray-100"
                    @click="edit(item.id)">
                    <td class="border-t px-6 py-4">
                        <img v-if="item.photo" class="block w-5 h-5 rounded-full mr-2 -my-2" :src="item.photo"/>
                        {{ item.id }}
                    </td>
                    <td class="border-t px-6 py-4">
                        <img v-if="item.photo" class="block w-5 h-5 rounded-full mr-2 -my-2" :src="item.photo"/>
                        {{ item.name }}
                    </td>
                </tr>
                <tr v-if="items.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">ยังไม่มีข้อมูล.</td>
                </tr>
            </table>
        </panel>
    </div>
</template>

<script>
    import Icon from '@/Shared/Icon'
    import mapValues from 'lodash/mapValues'
    import SearchFilter from '@/Shared/SearchFilter'
    import AppLayout from "@/Layouts/AppLayout";
    import DialogModal from "@/Jetstream/DialogModal";
    import JetButton from "@/Jetstream/Button";
    import JetActionMessage from "@/Jetstream/ActionMessage";
    import Create from "@/Pages/Customers/Create";
    import AButton from "@/A/AButton";
    import Panel from "@/A/Panel";

    export default {
        metaInfo: {title: 'Customers'},
        components: {
            Panel,
            AppLayout,
            AButton,
            Create,
            JetButton,
            JetActionMessage,
            DialogModal,
            Icon,
            SearchFilter,
        },
        layout: AppLayout,
        props: {
            items: Array,
            filters: Object,
        },
        data() {
            return {
                formSearch: {
                    search: this.filters.search,
                    role: this.filters.role,
                    trashed: this.filters.trashed,
                },
                showProductImport: false,
            }
        },
        methods: {
            reset() {
                this.form = mapValues(this.form, () => null)
            },
            edit(id) {
                this.$inertia.visit(route('products.edit', id));
            }

        },
    }
</script>
