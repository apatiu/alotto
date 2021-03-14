<template>

    <vue-next-select v-model="selected"
                     :options="options"></vue-next-select>
</template>

<script>
    import VueNextSelect from "vue-next-select";

    export default {
        name: "SelectSupplier",
        components: {VueNextSelect},
        props: ['value', 'options'],
        data() {
            return {
                option: ['a', 'b'],
                items: this.options ?? this.$page.props.suppliers,
                selected: this.value
            }
        }
    }
</script>

<style scoped>

</style>
