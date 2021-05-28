<template>
    <div>
        <div>
            <Divider/>

            <!-- Add Team Member -->
            <jet-form-section @submitted="addTeamMember">
                <template #title>
                    ตั้งค่างานขายฝาก
                </template>

                <template #form>
                    <div class="p-fluid">
                        <div class="p-field">
                            <label>อายุสัญญา (เดือน)</label>
                            <InputNumber v-model="form.life"/>
                        </div>
                        <div class="p-field">
                            <label>อัตตราดอกเบี้ยเริ่มต้น</label>
                            <InputPercent v-model="form.intr_rate"/>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                        Added.
                    </jet-action-message>

                    <Button :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            type="submit">
                        เพิ่ม
                    </Button>
                </template>
            </jet-form-section>
        </div>
    </div>
</template>

<script>
import InputError from "@/Jetstream/InputError";
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetActionSection from "@/Jetstream/ActionSection";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import ALabel from "@/A/ALabel";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import JetSectionBorder from "@/Jetstream/SectionBorder";
import InputPercent from "@/A/InputPercent";

export default {
    name: "PawnConfig",
    components: {
        InputPercent,
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
            form: this.$inertia.form({
                life: null,
                intr_rate: null,
                min_intr: null,
                intr_rate_by_price: null,
                intr_discount_by_day: null
            }),

        }
    },

    methods: {

        update() {
            this.updateRoleForm.put(route('team-members.update', [this.team, this.managingRoleFor]), {
                preserveScroll: true,
                onSuccess: () => (this.currentlyManagingRole = false),
            })
        },
    },
}
</script>

<style scoped>

</style>
