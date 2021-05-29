<template>
    <div>
        <div v-if="userPermissions.canAddTeamMembers">
            <Divider/>


            <!-- Add Team Member -->
            <jet-form-section @submitted="addTeamMember">
                <template #title>
                    พนักงาน
                </template>

                <template #description>
                    สร้างพนักงานใหม่และกำหนดสิทธ์ให้พนักงาน
                </template>

                <template #form>
                    <ButtonCreate label="สร้างพนักงานใหม่" @click="dlgCreateuser=true"></ButtonCreate>
                    <!-- Member Username -->
                    <div class="col-span-6 sm:col-span-4 mt-8">
                        <a-label for="username" value="Username"/>
                        <jet-input id="username" type="text" class="mt-1 block w-full"
                                   v-model="addTeamMemberForm.username"/>
                        <input-error :model-value="addTeamMemberForm.errors.username" class="mt-2"/>
                    </div>

                    <!-- Role -->
                    <div class="col-span-6 lg:col-span-4" v-if="availableRoles.length > 0">
                        <a-label for="roles" value="Role"/>
                        <FormInputError :message="addTeamMemberForm.errors.role" class="mt-2"/>

                        <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                            <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue"
                                    :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableRoles).length - 1}"
                                    @click="addTeamMemberForm.role = role.key"
                                    v-for="(role, i) in availableRoles"
                                    :key="role.key">
                                <div
                                    :class="{'opacity-50': addTeamMemberForm.role && addTeamMemberForm.role != role.key}">
                                    <!-- Role Name -->
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-600"
                                             :class="{'font-semibold': addTeamMemberForm.role == role.key}">
                                            {{ role.name }}
                                        </div>

                                        <svg v-if="addTeamMemberForm.role == role.key"
                                             class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round"
                                             stroke-linejoin="round" stroke-width="2" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>

                                    <!-- Role Description -->
                                    <div class="mt-2 text-xs text-gray-600">
                                        {{ role.description }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="addTeamMemberForm.recentlySuccessful" class="mr-3">
                        Added.
                    </jet-action-message>

                    <Button :class="{ 'opacity-25': addTeamMemberForm.processing }"
                            :disabled="addTeamMemberForm.processing"
                            type="submit">
                        เพิ่ม
                    </Button>
                </template>
            </jet-form-section>
        </div>

        <div v-if="team.team_invitations.length > 0 && userPermissions.canAddTeamMembers">
            <jet-section-border/>

            <!-- Team Member Invitations -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Pending Team Invitations
                </template>

                <template #description>
                    These people have been invited to your team and have been sent an invitation email. They may join
                    the team by accepting the email invitation.
                </template>

                <!-- Pending Team Member Invitation List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="invitation in team.team_invitations"
                             :key="invitation.id">
                            <div class="text-gray-600">{{ invitation.email }}</div>

                            <div class="flex items-center">
                                <!-- Cancel Team Invitation -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                        @click="cancelTeamInvitation(invitation)"
                                        v-if="userPermissions.canRemoveTeamMembers">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>

        <div v-if="team.users.length > 0">
            <jet-section-border/>

            <!-- Manage Team Members -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    พนักงานในสาขา
                </template>

                <template #description>

                </template>

                <!-- Team Member List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="user in team.users" :key="user.id">
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full" :src="user.profile_photo_url" :alt="user.name">
                                <div class="ml-4">{{ user.name }}</div>
                            </div>

                            <div class="flex items-center">
                                <!-- Manage Team Member Role -->
                                <button class="ml-2 text-sm text-gray-400 underline"
                                        @click="manageRole(user)"
                                        v-if="userPermissions.canAddTeamMembers && availableRoles.length">
                                    {{ displayableRole(user.membership.role) }}
                                </button>

                                <div class="ml-2 text-sm text-gray-400" v-else-if="availableRoles.length">
                                    {{ displayableRole(user.membership.role) }}
                                </div>

                                <!-- Leave Team -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500"
                                        @click="confirmLeavingTeam"
                                        v-if="$page.props.user.id === user.id">
                                    Leave
                                </button>

                                <!-- Remove Team Member -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500"
                                        @click="confirmTeamMemberRemoval(user)"
                                        v-if="userPermissions.canRemoveTeamMembers">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>

        <!-- Role Management Modal -->
        <jet-dialog-modal :show="currentlyManagingRole" @close="currentlyManagingRole = false">
            <template #title>
                Manage Role
            </template>

            <template #content>
                <div v-if="managingRoleFor">
                    <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                        <button type="button"
                                class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue"
                                :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i !== Object.keys(availableRoles).length - 1}"
                                @click="updateRoleForm.role = role.key"
                                v-for="(role, i) in availableRoles"
                                :key="role.key">
                            <div :class="{'opacity-50': updateRoleForm.role && updateRoleForm.role !== role.key}">
                                <!-- Role Name -->
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600"
                                         :class="{'font-semibold': updateRoleForm.role === role.key}">
                                        {{ role.name }}
                                    </div>

                                    <svg v-if="updateRoleForm.role === role.key" class="ml-2 h-5 w-5 text-green-400"
                                         fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>

                                <!-- Role Description -->
                                <div class="mt-2 text-xs text-gray-600">
                                    {{ role.description }}
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </template>

            <template #footer>
                <ButtonCancel @click="currentlyManagingRole = false" />

                <ButtonSave class="ml-2" @click="updateRole" :class="{ 'opacity-25': updateRoleForm.processing }"
                        :disabled="updateRoleForm.processing" />
            </template>
        </jet-dialog-modal>

        <!-- Leave Team Confirmation Modal -->
        <jet-confirmation-modal :show="confirmingLeavingTeam" @close="confirmingLeavingTeam = false">
            <template #title>
                Leave Team
            </template>

            <template #content>
                Are you sure you would like to leave this team?
            </template>

            <template #footer>
                <jet-secondary-button @click="confirmingLeavingTeam = false">
                    Cancel
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click="leaveTeam" :class="{ 'opacity-25': leaveTeamForm.processing }"
                                   :disabled="leaveTeamForm.processing">
                    Leave
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>

        <!-- Remove Team Member Confirmation Modal -->
        <jet-confirmation-modal :show="teamMemberBeingRemoved" @close="teamMemberBeingRemoved = null">
            <template #title>
                ลบพนักงาน
            </template>

            <template #content>
                Are you sure you would like to remove this person from the team?
            </template>

            <template #footer>
                <jet-secondary-button @click="teamMemberBeingRemoved = null">
                    ยกเลิก
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click="removeTeamMember"
                                   :class="{ 'opacity-25': removeTeamMemberForm.processing }"
                                   :disabled="removeTeamMemberForm.processing">
                    ลบ
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
        <Dialog v-model:visible="dlgCreateuser">
            <FormCreateUser @cancel="dlgCreateuser=false"></FormCreateUser>
        </Dialog>
    </div>
</template>

<script>
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetActionSection from '@/Jetstream/ActionSection'

import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
import JetDangerButton from '@/Jetstream/DangerButton'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import ALabel from "@/A/ALabel"
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import InputError from "@/Jetstream/InputError";
import ButtonCreate from "@/A/ButtonCreate";
import FormCreateUser from "@/Pages/Users/FormCreateUser";
import ButtonSave from "@/A/ButtonSave";
import ButtonCancel from "@/A/ButtonCancel";

export default {
    components: {
        ButtonSave,
        ButtonCancel,
        FormCreateUser,
        ButtonCreate,
        InputError,
        JetActionMessage,
        JetActionSection,
        JetConfirmationModal,
        JetDangerButton,
        JetDialogModal,
        JetFormSection,
        JetInput,
        JetInputError,
        ALabel,
        JetSecondaryButton,
        JetSectionBorder,
    },

    props: [
        'team',
        'availableRoles',
        'userPermissions'
    ],

    data() {
        return {
            addTeamMemberForm: this.$inertia.form({
                username: null,
                role: null,
            }),

            updateRoleForm: this.$inertia.form({
                role: null,
            }),

            leaveTeamForm: this.$inertia.form(),
            removeTeamMemberForm: this.$inertia.form(),

            currentlyManagingRole: false,
            managingRoleFor: null,
            confirmingLeavingTeam: false,
            teamMemberBeingRemoved: null,
            dlgCreateuser: false,
        }
    },

    methods: {
        addTeamMember() {
            console.log('Add team member.')
            this.addTeamMemberForm.post(route('team-members.store', this.team), {
                errorBag: 'addTeamMemberForm',
                preserveScroll: true,
                onSuccess: () => this.addTeamMemberForm.reset(),
            });
        },

        cancelTeamInvitation(invitation) {
            this.$inertia.delete(route('team-invitations.destroy', invitation), {
                preserveScroll: true
            });
        },

        manageRole(teamMember) {
            this.managingRoleFor = teamMember
            this.updateRoleForm.role = teamMember.membership.role
            this.currentlyManagingRole = true
        },

        updateRole() {
            this.updateRoleForm.put(route('team-members.update', [this.team, this.managingRoleFor]), {
                preserveScroll: true,
                onSuccess: () => (this.currentlyManagingRole = false),
            })
        },

        confirmLeavingTeam() {
            this.confirmingLeavingTeam = true
        },

        leaveTeam() {
            this.leaveTeamForm.delete(route('team-members.destroy', [this.team, this.$page.props.user]))
        },

        confirmTeamMemberRemoval(teamMember) {
            this.teamMemberBeingRemoved = teamMember
        },

        removeTeamMember() {
            this.removeTeamMemberForm.delete(route('team-members.destroy', [this.team, this.teamMemberBeingRemoved]), {
                errorBag: 'removeTeamMember',
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => (this.teamMemberBeingRemoved = null),
            })
        },

        displayableRole(role) {
            return this.availableRoles.find(r => r.key === role).name
        },
    },
}
</script>
