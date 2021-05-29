<template>
        <div>
            <h1 class="mb-8 font-bold text-3xl">พนักงาน</h1>
            <div class="flex space-x-2">
                <Button @click="create">สร้างพนักงาน</Button>
            </div>
            <DataTable :value="users" rowHover
                       @row-click="$inertia.visit(route('users.edit',$event.data.id))">
                <Column field="name" header="ชื่อ"></Column>
                <Column field="email" header="อีเมล์"></Column>
                <Column field="role" header="ตำแหน่ง"></Column>
            </DataTable>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4">ชื่อ</th>
                        <th class="px-6 pt-6 pb-4">อีเมล์</th>
                        <th class="px-6 pt-6 pb-4" colspan="2">ตำแหน่ง</th>
                    </tr>
                    <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t">
                            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500"
                                          :href="route('users.edit', user.id)">
                                <img v-if="user.photo" class="block w-5 h-5 rounded-full mr-2 -my-2" :src="user.photo"/>
                                {{ user.name }}
                                <icon v-if="user.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2"/>
                            </inertia-link>
                        </td>
                        <td class="border-t">
                            {{ user.email }}
                        </td>
                        <td class="border-t">
                            {{ user.owner ? 'Owner' : 'User' }}
                        </td>
                    </tr>
                    <tr v-if="users.length === 0">
                        <td class="border-t px-6 py-4" colspan="4">No users found.</td>
                    </tr>
                </table>
            </div>
        </div>
    {{ users }}
</template>

<script>
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import AppLayout from "@/Layouts/AppLayout";
import DialogModal from "@/Jetstream/DialogModal";

import ALink from "@/A/ALink";

export default {
    metaInfo: {title: 'Users'},
    components: {
        ALink,
        DialogModal,
        Icon,
    },
    layout: AppLayout,
    props: {
        users: Array,
        filters: Object,
    },
    data() {
        return {
            form: {
                search: this.filters.search,
                role: this.filters.role,
                trashed: this.filters.trashed,
            },
            dlgUser: false
        }
    },
    watch: {
        form: {
            handler: throttle(function () {
                let query = pickBy(this.form)
                this.$inertia.replace(this.route('users', Object.keys(query).length ? query : {remember: 'forget'}))
            }, 150),
            deep: true,
        },
    },
    methods: {
        create() {
            this.$refs.dlgUser.create()
        },
        reset() {
            this.form = mapValues(this.form, () => null)
        },
        createUser() {
        }
    },
}
</script>
