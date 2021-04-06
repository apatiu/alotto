<template>
    <Toast :baseZIndex="9999" :autoZIndex="false"/>
    <ConfirmDialog></ConfirmDialog>
    <div class="layout-wrapper">
        <AppTopBar></AppTopBar>
        <div class="p-col-12">
            <slot></slot>
        </div>
    </div>

</template>

<script>

import AppTopBar from "@/Layouts/AppTopBar";

export default {
    components: {
        AppTopBar
    },

    data() {
        return {
            layoutMode: 'static',
            layoutColorMode: 'dark',
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
        }
    },
    computed: {
        containerClass() {
            return [
                'layout-wrapper', {
                    'layout-overlay': this.layoutMode === 'overlay',
                    'layout-static': this.layoutMode === 'static',
                    'layout-static-sidebar-inactive': this.staticMenuInactive && this.layoutMode === 'static',
                    'layout-overlay-sidebar-active': this.overlayMenuActive && this.layoutMode === 'overlay',
                    'layout-mobile-sidebar-active': this.mobileMenuActive,
                    'p-ripple-disabled': this.$primevue.config.ripple === false
                }];
        },
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
