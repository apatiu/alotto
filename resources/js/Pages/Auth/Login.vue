<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo/>
        </template>

        <jet-validation-errors class="mb-4"/>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <label for="username">Username</label>
                <InputText id="username" type="text"
                           class="mt-1 block w-full"
                           v-model="form.username" required
                           autofocus/>
            </div>

            <div class="mt-4">
                <label for="password">Password</label>
                <InputText id="password"
                           type="password"
                           class="mt-1 block w-full" v-model="form.password" required
                           autocomplete="current-password"/>
            </div>

            <div class="block mt-4">
                <div class="flex items-center">
                    <Checkbox v-model="form.remember" :binary="true"></Checkbox>
                    <span class="ml-2 text-sm text-gray-600">จดจำบัญชีไว้</span>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link v-if="canResetPassword"
                              :href="route('password.request')"
                              class="underline text-sm text-gray-600 hover:text-gray-900 mr-2">
                    ลืมรหัสผ่าน?
                </inertia-link>

                <Button type="submit" class="ml-4" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                    เข้าสู่ระบบ
                </Button>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'

import JetInput from '@/Jetstream/Input'
import ALabel from "@/A/ALabel"
import JetValidationErrors from '@/Jetstream/ValidationErrors'

export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,

        JetInput,
        ALabel,
        JetValidationErrors
    },

    props: {
        canResetPassword: Boolean,
        status: String
    },

    data() {
        return {
            checked: false,

            form: this.$inertia.form({
                username: '',
                password: '',
                remember: false
            })

        }
    },

    methods: {
        submit() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                this.form
                    .transform(data => ({
                        ...data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            });
        }
    }
}
</script>
