<template>
    <div class="flex">
        <img
            :src="imgSrc" alt=""
            class="h-10 w-10"
            @click="imgDialog=true"
            :style="styleObject">
    </div>

    <capture-image v-model:visible="camDialog" @captured="onCaptured" :closable="false"></capture-image>
    <Dialog v-model:visible="imgDialog" style="width:450px;" header="ภาพ">
        <div class="flex-col items-center space-y-2">
            <img :src="imgSrc" class="max-h-60">
            <div class="flex items-center justify-center space-x-2">
                <Button icon="pi pi-times" label="ลบภาพ"
                        class="p-button-danger"
                        @click="clearItemImg"></Button>
                <Button @click="camDialog=true" icon="pi pi-camera" label="จับภาพ"></Button>
                <Button @click="imgDialog=false" label="ปิด"></Button>
            </div>
        </div>
    </Dialog>
</template>

<script>
import CaptureImage from "@/A/CaptureImage";

export default {
    name: "InputImage",
    components: {CaptureImage},
    props: {
        modelValue: String,
        thumbnailWidth: {default: 32},
        thumbnailHeight: {default: 32}
    },
    data() {
        return {
            imgDialog: false,
            camDialog: false,
            defaultImg: 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaWQ9IkxheWVyXzEiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI0IDI0OyIgdmVyc2lvbj0iMS4xIiB2aWV3Qm94PSIwIDAgMjQgMjQiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxwYXRoIGQ9Ik0xOS4yMzUsMS43NUg0Ljc2NWMtMS42NjIsMC0zLjAxNSwxLjM1Mi0zLjAxNSwzLjAxNXYxNC40NzFjMCwxLjY2MiwxLjM1MiwzLjAxNSwzLjAxNSwzLjAxNWgxNC40NzEgIGMxLjY2MiwwLDMuMDE1LTEuMzUyLDMuMDE1LTMuMDE1VjQuNzY1QzIyLjI1LDMuMTAyLDIwLjg5OCwxLjc1LDE5LjIzNSwxLjc1eiBNMjEuMDQ0LDE5LjIzNWMwLDAuOTk3LTAuODExLDEuODA5LTEuODA5LDEuODA5ICBINC43NjVjLTAuOTk3LDAtMS44MDktMC44MTEtMS44MDktMS44MDl2LTIuMTgxbDQuODY2LTUuNDA3bDYuNTczLDUuOTc1bDMuNjM1LTMuMDI5bDMuMDE1LDIuNTEyVjE5LjIzNXogTTIxLjA0NCwxNS41MzYgIGwtMy4wMTUtMi41MTJsLTMuNjAxLDNMNy43MzcsOS45NGwtNC43ODEsNS4zMTJWNC43NjVjMC0wLjk5NywwLjgxMS0xLjgwOSwxLjgwOS0xLjgwOWgxNC40NzFjMC45OTcsMCwxLjgwOSwwLjgxMSwxLjgwOSwxLjgwOSAgVjE1LjUzNnoiLz48cGF0aCBkPSJNMTYuMjIxLDEwLjc5NGMxLjY2MiwwLDMuMDE1LTEuMzUyLDMuMDE1LTMuMDE1cy0xLjM1Mi0zLjAxNS0zLjAxNS0zLjAxNWMtMS42NjIsMC0zLjAxNSwxLjM1Mi0zLjAxNSwzLjAxNSAgUzE0LjU1OCwxMC43OTQsMTYuMjIxLDEwLjc5NHogTTE2LjIyMSw1Ljk3MWMwLjk5NywwLDEuODA5LDAuODExLDEuODA5LDEuODA5cy0wLjgxMSwxLjgwOS0xLjgwOSwxLjgwOXMtMS44MDktMC44MTEtMS44MDktMS44MDkgIFMxNS4yMjMsNS45NzEsMTYuMjIxLDUuOTcxeiIvPjwvc3ZnPg=='
        }
    },
    computed: {
        imgSrc() {
            return this.modelValue ? this.modelValue : this.defaultImg
        },
        styleObject() {
            return {
                width: this.thumbnailWidth,
                height: this.thumbnailHeight
            }
        }
    },
    methods: {
        editItemImg(index) {
            this.editingItemIndex = index;
            this.camDialog = true;
        },
        onCaptured(e) {
            this.$emit('update:modelValue', e);
            this.camDialog = false;
        },
        clearItemImg(i) {
            this.$emit('update:modelValue', null)
        },
    }
}
</script>

<style scoped>

</style>
