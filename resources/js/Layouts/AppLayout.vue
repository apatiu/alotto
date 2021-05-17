<template>
    <div :class="containerClass" @click="onWrapperClick" ref="mainContainer">
        <AppTopBar @menu-toggle="onMenuToggle"></AppTopBar>
        <transition name="layout-sidebar">
            <div :class="sidebarClass" @click="onSidebarClick" v-show="isSidebarVisible()">
                <div class="layout-logo">
                    <img :src="'/' + $page.props.company_logo_url" class="w-20 m-auto" alt="">
                </div>

                <AppProfile/>
                <AppMenu :model="menu" @menuitem-click="onMenuItemClick"/>
            </div>
        </transition>

        <div class="layout-main">
            <slot></slot>
        </div>

        <Toast :baseZIndex="9999" :autoZIndex="false"/>
        <ConfirmDialog></ConfirmDialog>
        <open-shift ref="openShift"/>
    </div>
    <div id="printable"></div>


</template>

<script>

import AppTopBar from "@/Layouts/AppTopBar";
import ApplicationMark from "@/Jetstream/ApplicationMark";
import AppMenu from "@/Layouts/AppMenu";
import AppProfile from "@/Layouts/AppProfile";
import OpenShift from "@/Pages/Shifts/OpenShift";

export default {
    components: {
        OpenShift,
        AppProfile,
        AppMenu,
        ApplicationMark,
        AppTopBar
    },
    data() {
        return {
            layoutMode: 'static',
            layoutColorMode: 'light',
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
            menu: [
                {
                    label: 'Dashboard',
                    icon: 'pi pi-fw pi-home',
                    url: route('dashboard')
                }, {
                    label: 'หน้าร้าน',
                    url: route('pos.index')
                }, {
                    label: 'ซื้อขายทอง',
                    items: [
                        {label: 'รายการ ซื้อ/ขาย/เปลี่ยน', url: route('sales.index')},
                    ]
                }, {
                    label: 'ขายฝาก',
                    url: route('pawns.index')
                }, {
                    label: 'ออมทอง',
                    url: route('savings.index')
                }, {
                    label: 'รับจ่าย',
                    url: route('payments.index')
                }, {
                    label: 'สินค้า',
                    items: [
                        {label: 'รายการสินค้า', url: route('products.index')},
                        {separator: true},
                        {label: 'นำเข้าสินค้า', url: route('stock-imports.index')}, {
                            label: 'ผู้จำหน่าย',
                            url: route('suppliers.index')
                        }, {
                            label: 'ประเภทสินค้า',
                            url: route('product-types.index')
                        }, {
                            label: 'ลายสินค้า',
                            url: route('product-designs.index')
                        }, {
                            label: 'ขนาดสินค้า',
                            url: route('product-sizes.index')
                        }, {
                            label: 'เปอร์เซ็นต์ทอง',
                            url: route('gold-percents.index')
                        }
                    ]
                }, {
                    label: 'ลูกค้า',
                    url: route('customers.index')
                }, {
                    label: 'กะทำงาน',
                    url: route('shifts.show-latest')
                }, {
                    label: 'รายงาน',
                    icon: 'pi pi-file-pdf',
                    items: [{
                        label: 'สินค้า',
                        items: [{
                            label: 'เคลื่อนไหวสินค้า', url: route('stock-cards.index')
                        }]
                    },
                        {
                            label: 'ทองเก่า',
                            items: [
                                {label: 'ความเคลื่อนไหวทองเก่า', url: route('oldgoldstocks.index')}
                            ]
                        }
                    ]
                }, {
                    label: 'Settings', icon: 'pi pi-cog',
                    visible: () => {
                        return this.$page.props.user_roles.admin;
                    },
                    items: [
                        {label: 'ตั้งค่าสาขา', url: route('teams.show', this.$page.props.user.current_team)},
                        {label: 'System', url: route('settings.index')},
                        {label: 'Users', url: route('users')},
                        {
                            label: 'API Token', url: route('api-tokens.index'),
                            visible: () => {
                                return this.$page.props.jetstream.hasApiFeatures
                            }
                        },
                    ]
                }
            ],

        }
    },
    mounted() {
        if (this.$page.props.shift.status === 'close') {
            this.$refs.openShift.show()
        }
    },
    computed: {
        containerClass() {
            return ['layout-wrapper', {
                'layout-overlay': this.layoutMode === 'overlay',
                'layout-static': this.layoutMode === 'static',
                'layout-static-sidebar-inactive': this.staticMenuInactive && this.layoutMode === 'static',
                'layout-overlay-sidebar-active': this.overlayMenuActive && this.layoutMode === 'overlay',
                'layout-mobile-sidebar-active': this.mobileMenuActive,
                'p-input-filled': this.$appState.inputStyle === 'filled',
                'p-ripple-disabled': this.$primevue.config.ripple === false
            }];
        },
        sidebarClass() {
            return ['layout-sidebar', {
                'layout-sidebar-dark': this.layoutColorMode === 'dark',
                'layout-sidebar-light': this.layoutColorMode === 'light'
            }];
        },
    },
    methods: {
        onMenuToggle() {
            this.menuClick = true;
            if (this.isDesktop()) {
                if (this.layoutMode === 'overlay') {
                    if (this.mobileMenuActive === true) {
                        this.overlayMenuActive = true;
                    }
                    this.overlayMenuActive = !this.overlayMenuActive;
                    this.mobileMenuActive = false;
                } else if (this.layoutMode === 'static') {
                    this.staticMenuInactive = !this.staticMenuInactive;
                }
            } else {
                this.mobileMenuActive = !this.mobileMenuActive;
            }
            event.preventDefault();
        },
        onMenuItemClick(event) {
            if (event.item && !event.item.items) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
        },
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
        isDesktop() {
            return window.innerWidth > 1024;
        },
        isSidebarVisible() {
            if (this.isDesktop()) {
                if (this.layoutMode === 'static')
                    return !this.staticMenuInactive;
                else if (this.layoutMode === 'overlay')
                    return this.overlayMenuActive;
                else
                    return true;
            } else {
                return true;
            }
        },
    },
}
</script>


<style scoped>
.main-content {
    margin-left: 73px;
}
</style>
