<template>
    <app-layout>
        <TabView>
            <TabPanel header="องค์กร">
                <div>
                    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                            <company-data :company="company"/>
                            <jet-section-border/>
                        </div>

                        <div v-if="$page.props.jetstream.canUpdatePassword">
                            <update-company-config :data="company_config" class="mt-10 sm:mt-0"/>
                            <jet-section-border/>
                        </div>

                        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                            <!--                    <two-factor-authentication-form class="mt-10 sm:mt-0" />-->
                            <jet-section-border/>
                        </div>

                        <!--                <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />-->

                        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                            <jet-section-border/>
                            <!--                    <delete-user-form class="mt-10 sm:mt-0" />-->
                        </template>

                    </div>
                </div>
            </TabPanel>
            <TabPanel header="บัญชีธนาคาร">
                <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                </div>
            </TabPanel>
        </TabView>

    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import CompanyData from './CompanyData'
import UpdateCompanyConfig from "@/Pages/Settings/UpdateCompanyConfig";

export default {
    props: [
        'company',
        'company_config',
    ],

    components: {
        BankAccounts,
        AppLayout,
        JetSectionBorder,
        CompanyData,
        UpdateCompanyConfig,
        PawnSettings

    },
}
</script>
