<template>
    <div class="p-field">
        <label>รางวัล</label>
        <Dropdown></Dropdown>
        <InputError :modelValue="errors" />
    </div>
</template>

<script>
import InputError from '@/Jetstream/InputError'

export default {
    name: "SelectLotterPrize",
    components: { InputError },
    props: ['modelValue', 'errors']
}
</script>

<style scoped>

</style>
