<template>
    <label>
        <span v-if="value">{{ value }}</span>
        <span v-else><slot></slot></span>
    </label>
</template>

<script>
    export default {
        name: 'ALabel',
        props: ['value']
    }
</script>
