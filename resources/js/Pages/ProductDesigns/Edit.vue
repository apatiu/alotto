<template>
    <div class="sm:p-4 ">
        <h1 class="mb-8 font-bold text-3xl">{{ item.name }}</h1>

            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-2">
                    <text-input
                            :model-value="item.id"
                            label="รหัสลูกค้า"
                            disabled/>
                </div>
                <div class="col-span-6">
                    <text-input
                            v-model="form.name"
                            :error="form.errors.name" label="ชื่อ"/>
                </div>
            </div>


                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    บันทึกแล้ว.
                </jet-action-message>

                <a-link color="secondary" href="suppliers.index">ยกเลิก</a-link>
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
    import ALink from "@/A/ALink";

    export default {
        metaInfo: {title: 'ผู้จำหน่าย'},
        components: {
            ALink,
            AButton,

            TextInput,

            JetActionMessage,
            Icon,
        },
        layout: AppLayout,
        props: ['item'],
        data() {
            return {
                form: this.$inertia.form({
                    id: this.item.id,
                    name: this.item.name
                })
            }
        },
        methods: {
            reset() {
                this.form = mapValues(this.form, () => null)
            },
            update() {
                this.form.put(route('suppliers.update', this.item.id),
                    {
                        preserveScroll: true
                    })
            }
        },
    }
</script>
