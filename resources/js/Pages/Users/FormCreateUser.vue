<template>

        <div class="p-fluid">
            <div class="p-field">
                <a-label for="name" value="ชื่อ"/>
                <InputText id="name" type="text" v-model="form.name"/>
                <FormInputError :message="form.errors.name"/>
            </div>
            <div class="p-field">
                <a-label for="username" value="Username"/>
                <InputText id="username" v-model="form.username"/>
                <FormInputError :message="form.errors.username"/>
            </div>
            <div class="p-field">
                <a-label for="password" value="Password"/>
                <InputText
                    id="password" type="password"
                    v-model="form.password"/>
                <FormInputError :message="form.errors.password"/>
            </div>
            <div class="p-field">
                <a-label for="password_confirmation" value="Password confirmation"/>
                <InputText
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"/>
                <FormInputError :message="form.errors.password_confirmation"/>
            </div>
        </div>
        <div class="flex justify-end space-x-2">
            <ButtonCancel @click="$emit('cancel')">Cancel</ButtonCancel>
            <ButtonSave @click="save">Save</ButtonSave>
        </div>
</template>

<script>
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError"
import ALabel from "@/A/ALabel";
import AInput from "@/A/AInput";
import ButtonCancel from "@/A/ButtonCancel";
import ButtonSave from "@/A/ButtonSave";
import FormInputError from "@/A/FormInputError";


export default {
    name: "FormCreateUser",
    components: {FormInputError, ButtonSave, ButtonCancel, AInput, ALabel, JetInput, JetInputError},
    data() {
        return {
            form: this.$inertia.form({
                name: null,
                username: null,
                password: null,
                password_confirmation: null,
                team_id: this.$page.props.user.current_team.id
            })
        }
    },
    methods: {
        create() {
            this.form.reset();
            this.$emit('update:visible', true)
        },
        save() {
            if (this.form.id) {

            } else {
                this.form.post(route('users.store'), {
                    errorBag: 'createUser',
                    preserveScroll: true
                });
            }
        }
    }
}
</script>

<style scoped>

</style>
