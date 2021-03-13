<template>
    <div class="sm:p-4">
        <h1 class="mb-8 font-bold text-3xl">ลูกค้า</h1>
        <div class="mb-6 flex justify-between items-center">
            <!--            <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">-->
            <!--                <label class="block text-gray-700">Role:</label>-->
            <!--                <select v-model="form.role" class="mt-1 w-full form-select">-->
            <!--                    <option :value="null" />-->
            <!--                    <option value="user">Manager</option>-->
            <!--                    <option value="owner">Officer</option>-->
            <!--                </select>-->
            <!--                <label class="mt-4 block text-gray-700">Trashed:</label>-->
            <!--                <select v-model="form.trashed" class="mt-1 w-full form-select">-->
            <!--                    <option :value="null" />-->
            <!--                    <option value="with">With Trashed</option>-->
            <!--                    <option value="only">Only Trashed</option>-->
            <!--                </select>-->
            <!--            </search-filter>-->
            <jet-button @click="create">
                <span>เพิ่ม</span>
            </jet-button>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">Name</th>
                    <th class="px-6 pt-6 pb-4">Email</th>
                    <th class="px-6 pt-6 pb-4" colspan="2">Role</th>
                </tr>
                <tr v-for="item in items" :key="item.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500"
                                      :href="route('users.edit', item.id)">
                            <img v-if="item.photo" class="block w-5 h-5 rounded-full mr-2 -my-2" :src="item.photo"/>
                            {{ item.name }}
                            <icon v-if="item.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2"/>
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('users.edit', item.id)"
                                      tabindex="-1">
                            {{ item.email }}
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('users.edit', item.id)"
                                      tabindex="-1">
                            {{ item.owner ? 'Owner' : 'User' }}
                        </inertia-link>
                    </td>
                    <td class="border-t w-px">
                        <inertia-link class="px-4 flex items-center" :href="route('users.edit', item.id)" tabindex="-1">
                            <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400"/>
                        </inertia-link>
                    </td>
                </tr>
                <tr v-if="items.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">ยังไม่มีข้อมูล.</td>
                </tr>
            </table>
        </div>
        <form-customer :show="showCreateModal"></form-customer>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Shared/SearchFilter'
import AppLayout from "@/Layouts/AppLayout";
import DialogModal from "@/Jetstream/DialogModal";
import JetButton from "@/Jetstream/Button";
import JetActionMessage from "@/Jetstream/ActionMessage";
import FormCustomer from "@/Pages/Customers/FormCustomer";

export default {
    metaInfo: {title: 'Customers'},
    components: {
        FormCustomer,
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
            showCreateModal: false,
        }
    },
    methods: {
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        create() {
            this.showCreateModal = true
        },

    },
}
</script>
