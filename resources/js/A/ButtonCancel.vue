<template>
    <Button :label="label" :icon="icon" class="p-button-text"
            @click="$emit('click',$event)"></Button>
</template>

<script>
export default {
    name: "ButtonCancel",
    props: {
        label: {default: 'ยกเลิก'},
        icon: {default: 'pi pi-cancel'}
    }
}
</script>

<style scoped>

</style>
