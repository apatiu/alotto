<template>
    <Button :label="label" :icon="icon"
            @click="$emit('click',$event)"></Button>
</template>

<script>
export default {
    name: "ButtonSave",
    props: {
        label: {default: 'บันทึก'},
        icon: {default: 'pi pi-save'}
    }
}
</script>

<style scoped>

</style>
