<template>
    <app-layout>
        <template #header>
            <h3 class="font-semibold text-gray-800 leading-tight">
                สร้างสาขา
            </h3>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <create-team-form />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import CreateTeamForm from './CreateTeamForm'

    export default {
        components: {
            AppLayout,
            CreateTeamForm,
        },
    }
</script>
