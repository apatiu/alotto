<template>
    <div>
        <jet-banner/>

        <div class="min-h-screen bg-gray-100">
            <navbar></navbar>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <div class="sidebar absolute p-1">
                <sidebar :items="sidebarItems"></sidebar>
                <slot name="sidebar"></slot>
            </div>

            <!-- Page Content -->
            <main class="main-content">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
    import Sidebar from "@/Shared/Sidebar";
    import Navbar from "@/Layouts/Navbar";

    export default {
        components: {
            Navbar,
            Sidebar,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
        },

        data() {
            return {
                sidebarItems: [
                    {
                        icon: 'users',
                        title: 'ลูกค้า',
                        route: 'customers.index'
                    }
                ]
            }
        },

        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                this.$inertia.post(route('logout'));
            },
        },
    }
</script>


<style scoped>
 .main-content {
     margin-left: 73px;
 }
</style>
