<template>
    <div class="layout-profile">
        <div class="flex justify-center">
            <button v-if="$page.props.jetstream.managesProfilePhotos"
                    @click="onClick"
                    class="p-link layout-profile-link w-full flex items-center justify-center">

                <img class="h-8 w-8 rounded-full object-cover"
                     :src="$page.props.user.profile_photo_url"
                     :alt="$page.props.user.name"/>
                {{ $page.props.user.name }}
                <i class="pi pi-fw pi-cog ml-3"></i>
            </button>

            <span v-else class="inline-flex rounded-md">
                <button type="button"
                        @click="onClick"
                        class="inline-flex items-center px-3 py-2 border
                        border-transparent text-sm leading-4 font-medium rounded-md
                        text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition
                        ease-in-out duration-150">
                    {{ $page.props.user.name }}
                    <svg class="ml-2 -mr-0.5 h-4 w-4"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                    {{ $page.props.user.name }}
                </button>
            </span>


        </div>
        <transition name="layout-submenu-wrapper">
            <ul v-show="expanded">
                <li>
                    <inertia-link :href="route('profile.show')" as="button" class="p-link">
                        <i class="pi pi-fw pi-user"></i><span>Profile</span></inertia-link>
                </li>
                <li>
                    <button class="p-link" @click="$inertia.post('/logout')"><i class="pi pi-fw pi-power-off"></i><span>Logout</span>
                    </button>
                </li>
            </ul>
        </transition>

    </div>
</template>

<script>
export default {
    name: 'AppProfile',
    data() {
        return {
            expanded: false
        }
    },
    methods: {
        onClick(event) {
            this.expanded = !this.expanded;
            event.preventDefault();
        }
    }
}
</script>

<style scoped>
</style>
