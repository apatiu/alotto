<template>
    <dialog-modal :show="show" max-width="lg">
        <template #title>
            สร้างสินค้า
        </template>
        <template #content>
            <div class="grid-cols-6 space-y-1">
                <div class="col-span-6">
                    สาขา: {{ $page.props.user.current_team.name }}
                </div>
                <div class="col-span-3">
                    <jet-label for="id" value="รหัสสินค้า"/>
                    <jet-input id="id" type="text" class="mt-1 block w-full" v-model="form.id"/>
                    <jet-input-error :message="form.errors.id" class="mt-2"/>
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
    name: "CreateProduct",
    components: {JetLabel, JetInput, JetInputError,  DialogModal},
    props: {
        value: Object,
        show: {type: Boolean, default: false},
        mode: {
            type: String,
            default: 'normal'
        }
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
