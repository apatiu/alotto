<template>
    <div v-show="message">
        <p class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
    <small v-for="error in errors" class="p-error">
        {{error.$message}}
    </small>
</template>

<script>
    export default {
        props: ['message','errors']
    }
</script>
