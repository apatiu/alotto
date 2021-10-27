<script setup>
// This starter template is using Vue 3 <script setup> SFCs
// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup
import HelloWorld from './components/HelloWorld.vue'
import {Viewer} from "@grapecity/activereports-vue";
</script>

<template>
    <img alt="Vue logo" src="./assets/logo.png"/>
    <HelloWorld msg="Hello Vue 3 + Vite"/>
    <Viewer v-bind:report="{ Uri: 'report.rdlx-json' }"></Viewer>
</template>

<style src="../node_modules/@grapecity/activereports/styles/ar-js-ui.css"></style>
<style src="../node_modules/@grapecity/activereports/styles/ar-js-viewer.css" ></style>
<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}
</style>
