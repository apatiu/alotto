<template>
    <div class="sm:p-4">
        <h1 class="mb-8 font-bold text-3xl">Users</h1>
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
            <a-link color='primary' href="users.create">
                <span>Create</span>
                <span class="hidden md:inline">User</span>
            </a-link>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">Name</th>
                    <th class="px-6 pt-6 pb-4">Email</th>
                    <th class="px-6 pt-6 pb-4" colspan="2">Role</th>
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
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('users.edit', user.id)"
                                      tabindex="-1">
                            {{ user.email }}
                        </inertia-link>
                    </td>
                    <td class="border-t">
                        <inertia-link class="px-6 py-4 flex items-center" :href="route('users.edit', user.id)"
                                      tabindex="-1">
                            {{ user.owner ? 'Owner' : 'User' }}
                        </inertia-link>
                    </td>
                    <td class="border-t w-px">
                        <inertia-link class="px-4 flex items-center" :href="route('users.edit', user.id)" tabindex="-1">
                            <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400"/>
                        </inertia-link>
                    </td>
                </tr>
                <tr v-if="users.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">No users found.</td>
                </tr>
            </table>
        </div>
        <create-user :show="showCreateModal" @close="showCreateModal=false"></create-user>
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
    import CreateUser from "@/Shared/CreateUser";
    import ALink from "@/A/ALink";

    export default {
        metaInfo: {title: 'Users'},
        components: {
            ALink,
            CreateUser,
            JetButton,
            DialogModal,
            Icon,
            SearchFilter,
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
                showCreateModal: false
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
            reset() {
                this.form = mapValues(this.form, () => null)
            },
            createUser() {
                this.showCreateModal = true;
            }
        },
    }
</script>
