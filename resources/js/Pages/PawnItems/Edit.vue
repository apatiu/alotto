<template>
    <div class="sm:p-4 ">
        <h1 class="mb-8 font-bold text-3xl">ลูกค้า</h1>


            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-2">
                    <text-input
                            :model-value="customer.id_label"
                            label="รหัสลูกค้า"
                            disabled/>
                </div>
                <div class="col-span-6">
                    <text-input
                            v-model="form.name"
                            :error="form.errors.name" label="ชื่อลูกค้า"/>
                </div>
            </div>

                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    บันทึกแล้ว.
                </jet-action-message>
                <a-button color="primary" @click="update">บันทึก</a-button>


    </div>
</template>

<script>
    import Icon from '@/Shared/Icon'
    import mapValues from 'lodash/mapValues'
    import AppLayout from "@/Layouts/AppLayout";

    import JetActionMessage from "@/Jetstream/ActionMessage";
    import TextInput from "@/Shared/TextInput";

    import AButton from "@/A/AButton";

    export default {
        metaInfo: {title: 'Customers'},
        components: {
            AButton,

            TextInput,

            JetActionMessage,
            Icon,
        },
        layout: AppLayout,
        props: ['customer'],
        data() {
            return {
                form: this.$inertia.form({
                    id: this.customer.id,
                    name: this.customer.name
                })
            }
        },
        methods: {
            reset() {
                this.form = mapValues(this.form, () => null)
            },
            update() {
                this.form.put(route('customers.update', this.customer.id),
                    {
                        preserveScroll: true
                    })
            }
        },
    }
</script>
