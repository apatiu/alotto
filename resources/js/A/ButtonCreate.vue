<template>
    <Button :label="label" :icon="icon" class="p-button-info"
            @click="$emit('click',$event)"></Button>
</template>

<script>
export default {
    name: "ButtonCreate",
    props: {
        label: {default: 'สร้าง'},
        icon: {default: 'pi pi-plus'}
    }
}
</script>

<style scoped>

</style>
