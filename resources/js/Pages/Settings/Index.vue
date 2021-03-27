<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ตั้งค่าระบบ
            </h2>
        </template>
        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <company-data :company="company" />

                    <jet-section-border />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <update-company-config :data="company_config" class="mt-10 sm:mt-0" />

                    <jet-section-border />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <pawn-settings :data="pawn_config" class="mt-10 sm:mt-0" />

                    <jet-section-border />
                </div>

                <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
<!--                    <two-factor-authentication-form class="mt-10 sm:mt-0" />-->

                    <jet-section-border />
                </div>

<!--                <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />-->

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <jet-section-border />

<!--                    <delete-user-form class="mt-10 sm:mt-0" />-->
                </template>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import CompanyData from './CompanyData'
import UpdateCompanyConfig from "@/Pages/Settings/UpdateCompanyConfig";
import PawnSettings from './PawnSettings'

export default {
    props: ['company','company_config','pawn_config'],

    components: {
        AppLayout,
        JetSectionBorder,
        CompanyData,
        UpdateCompanyConfig,
        PawnSettings

    },
}
</script>
