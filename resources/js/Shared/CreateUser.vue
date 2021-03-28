<template>
    <dialog-modal :show="show" max-width="sm">
        <template #title>
            สร้างผู้ใช้ใหม่
        </template>
        <template #content>
            <div class="space-y-3">
                <div class="">
                    <jet-label for="name" value="ชื่อ"/>
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                    <jet-input-error :message="form.errors.name" class="mt-2"/>
                </div>
                <div class="">
                    <jet-label for="username" value="Username"/>
                    <jet-input id="username" type="text" class="mt-1 block w-full" v-model="form.username"/>
                    <jet-input-error :message="form.errors.username" class="mt-2"/>
                </div>
                <div class="">
                    <jet-label for="password" value="Password"/>
                    <jet-input id="password" type="text" class="mt-1 block w-full" v-model="form.password"/>
                    <jet-input-error :message="form.errors.password" class="mt-2"/>
                </div>
                <div class="">
                    <jet-label for="password_confirmation" value="Password confirmation"/>
                    <jet-input id="password_confirmation" type="text" class="mt-1 block w-full"
                               v-model="form.password"/>
                    <jet-input-error :message="form.errors.password_confirmation" class="mt-2"/>
                </div>
                <div>
                    <jet-dropdown>
                        <template #trigger>
                            Team
                        </template>
                    </jet-dropdown>
                </div>
            </div>
        </template>
        <template #footer>
            <Button type="button" color="secondary" @click="$emit('close')">Cancel</Button>
            <Button color="primary" class="ml-2" @click="save">Save</Button>
        </template>
    </dialog-modal>
</template>

<script>
import DialogModal from "@/Jetstream/DialogModal";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError"


export default {
    name: "CreateUser",
    components: {JetLabel, JetInput, JetInputError,  DialogModal},
    props: {
        value: Object,
        show: {type: Boolean, default: false}
    },
    data() {
        return {
            form: this.$inertia.form({
                _method: 'POST',
                name: '',
                username: '',
                password: '',
                password_confirmation: ''
            })
        }
    },
    methods: {
        save() {
            this.form.post(route('users.store'), {
                errorBag: 'createUser',
                preserveScroll: true
            });
        }
    }
}
</script>

<style scoped>

</style>
