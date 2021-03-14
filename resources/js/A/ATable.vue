<template>
    <table class="w-full border">
        <thead>
        <tr class="border-b">
            <th v-for="header in headers">{{ header.label}}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row in rows">
            <td v-for="header in headers">{{ row[header.name]}}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "ATable",
        props: {
            headers: {default: null},
            value: {default: null},
            data() {
                return {
                    rows: this.value
                }
            }
        }
    }
</script>

<style scoped>

</style>
