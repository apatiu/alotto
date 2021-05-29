<template>
    <AppLayout>
        <template #header>
            <h3 class="mb-8 font-bold">รายการสาขา</h3>
        </template>
        <div class="sm:p-4">
            <div class="flex">
                <ButtonCreate @click="$inertia.get(route('teams.create'))"/>
            </div>
            <DataTable :value="teams" @row-click="onRowClick">
                <Column field="company_name" header="บริษัท"></Column>
                <Column field="name" header="สาขา"></Column>
                <Column field="branch_number" header="เลขสาขา"></Column>
                <Column field="addr" header="ที่อยู่"></Column>
                <Column field="tax_id" header="เลขผู้เสียภาษี"></Column>
                <Column field="phone" header="เบอร์โทร"></Column>
                <Column field="profile_photo_path" header="โลโก้">
                    <template #body="props">
                        <img :src="props.data.profile_photo_url" alt="" class="w-20">
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>

<script>
import Icon from '@/Shared/Icon'
import AppLayout from "@/Layouts/AppLayout";
import JetActionMessage from "@/Jetstream/ActionMessage";
import ButtonCreate from "@/A/ButtonCreate"


export default {
    metaInfo: {title: 'สินค้า'},
    components: {
        AppLayout,
        ButtonCreate,
        JetActionMessage,
        Icon,
    },
    props: {
        teams: Array,
        filters: Object,
    },
    data() {
        return {}
    },
    methods: {
        onRowClick(e) {
            this.$inertia.get(route('teams.show', e.data.id));
        }
    },
}
</script>
