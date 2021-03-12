<template>
    <dialog-modal :show="show" max-width="lg">
        <template #title>
            สร้างสินค้า
        </template>
        <template #content>
            <div class="grid-cols-3">
                <div class="col-span-3">
                    สาขา: {{ $page.props.user.current_team.name }}
                </div>
                <div class="">
                    <jet-label for="name" value="ชื่อ"/>
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                    <jet-input-error :message="form.errors.name" class="mt-2"/>
                </div>
            </div>
        </template>
        <template #footer>
            <jet-button type="button" color="secondary" @click="$emit('close')">Cancel</jet-button>
            <jet-button color="primary" class="ml-2" @click="save">Save</jet-button>
        </template>
    </dialog-modal>
</template>

<script>
import DialogModal from "@/Jetstream/DialogModal";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError"
import JetButton from "@/Jetstream/Button";
import JetDropdown from "@/Shared/Dropdown";

export default {
    name: "CreateProduct",
    components: {JetDropdown, JetLabel, JetInput, JetInputError, JetButton, DialogModal},
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
