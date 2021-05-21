<template>
    <Dialog modal v-model:visible="visible" header="พนักงาน">
        <div class="p-fluid">
            <div class="p-field">
                <a-label for="name" value="ชื่อ"/>
                <InputText id="name" type="text" v-model="form.name"/>
                <jet-input-error :message="form.errors.name" class="mt-2"/>
            </div>
            <div class="p-field">
                <a-label for="username" value="Username"/>
                <a-input id="username" type="text" class="mt-1 block w-full" v-model="form.username"/>
                <jet-input-error :message="form.errors.username" class="mt-2"/>
            </div>
            <div class="p-field">
                <a-label for="password" value="Password"/>
                <InputText
                    id="password" type="password"
                    v-model="form.password"/>
                <jet-input-error :message="form.errors.password" class="mt-2"/>
            </div>
            <div class="p-field">
                <a-label for="password_confirmation" value="Password confirmation"/>
                <InputText
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"/>
                <jet-input-error :message="form.errors.password_confirmation" class="mt-2"/>
            </div>
        </div>
        <template #footer>
            <Button type="button" color="secondary" @click="$emit('close')">Cancel</Button>
            <Button color="primary" class="ml-2" @click="save">Save</Button>
        </template>
    </Dialog>
</template>

<script>
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError"
import ALabel from "@/A/ALabel";
import AInput from "@/A/AInput";


export default {
    name: "FormUser",
    components: {AInput, ALabel, JetInput, JetInputError},
    props: {
        data: Object,
        visible: {type: Boolean, default: false}
    },
    data() {
        return {
            form: this.$inertia.form(this.data)
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
