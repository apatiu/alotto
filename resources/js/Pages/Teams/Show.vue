<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ตั้งค่าสาขา
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <update-team-name-form :team="team" :permissions="permissions"/>
                <Divider />
                <pawn-settings :team="team" class="mt-10 sm:mt-0"/>
                <Divider />
                <team-member-manager class="mt-10 sm:mt-0"
                                     :team="team"
                                     :available-roles="availableRoles"
                                     :user-permissions="permissions"/>

                <template v-if="permissions.canDeleteTeam && ! team.personal_team">
                    <jet-section-border/>

                    <delete-team-form class="mt-10 sm:mt-0" :team="team"/>
                </template>
            </div>
        </div>
    </app-layout>
</template>

<script>
import TeamMemberManager from './TeamMemberManager'
import AppLayout from '@/Layouts/AppLayout'
import DeleteTeamForm from './DeleteTeamForm'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import UpdateTeamNameForm from './UpdateTeamNameForm'
import PawnConfig from "@/Pages/Teams/PawnConfig"
import PawnSettings from "@/Pages/Teams/PawnSettings";

export default {
    props: [
        'team',
        'availableRoles',
        'permissions',
    ],

    components: {
        PawnSettings,
        PawnConfig,
        AppLayout,
        DeleteTeamForm,
        JetSectionBorder,
        TeamMemberManager,
        UpdateTeamNameForm,
    },
}
</script>
