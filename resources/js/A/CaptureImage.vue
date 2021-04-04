<template>

    <Dialog :visible="visible"
            style="width: 750px; "
            :closable="false"
            :closeOnEscape="false"
            modal>
        <div class="p-grid p-mt-2">
            <div class="p-md-6">
                <webcam
                    ref="webcam"
                    :device-id="deviceId"
                    width="300"
                    height="300"
                    @started="onStarted"
                    @stopped="onStopped"
                    @error="onError"
                    @cameras="onCameras"
                    @camera-change="onCameraChange"
                />
            </div>
            <div class="p-md-6 flex flex-col space-y-2">
                <select v-model="camera" class="w-full">
                    <option>-- Select Device --</option>
                    <option
                        v-for="device in devices"
                        :key="device.deviceId"
                        :value="device.deviceId"
                    >{{ device.label }}
                    </option>
                </select>
                <Button label="จับภาพ" @click="onCapture"></Button>
                <Button label="ยกเลิก" class="p-button-secondary" @click="$emit('update:visible',false)"></Button>
                <!--                        <Button type="button" class="btn btn-danger" @click="onStop">Stop Camera</Button>-->
                <!--                        <Button type="button" class="btn btn-success" @click="onStart">Start Camera</Button>-->

            </div>

        </div>
    </Dialog>
</template>

<script>
import Webcam from "@/A/Webcam";

export default {
    name: "CaptureImage",
    components: {Webcam},
    props: ['visible'],
    data() {
        return {
            img: null,
            camera: null,
            deviceId: null,
            devices: []
        };
    },
    computed: {
        device: function () {
            return this.devices.find(n => n.deviceId === this.deviceId);
        }
    },
    watch: {
        visible(val) {
           if (!val) {
               this.$refs.webcam.stop();
           } else {
               this.$nextTick(() => {
                   this.$refs.webcam.start();
               })
           }
        },
        camera: function (id) {
            this.deviceId = id;
        },
        devices: function () {
            // Once we have a list select the first one
            const [first, ...tail] = this.devices;
            if (first) {
                this.camera = first.deviceId;
                this.deviceId = first.deviceId;
            }
        }
    },
    methods: {
        onCapture() {
            this.img = this.$refs.webcam.capture();
            this.$emit('captured', this.img);
        },
        onStarted(stream) {
            console.log("On Started Event", stream);
        },
        onStopped(stream) {
            console.log("On Stopped Event", stream);
        },
        onStop() {
            this.$refs.webcam.stop();
        },
        onStart() {
            this.$refs.webcam.start();
        },
        onError(error) {
            console.log("On Error Event", error);
        },
        onCameras(cameras) {
            this.devices = cameras;
            console.log("On Cameras Event", cameras);
        },
        onCameraChange(deviceId) {
            this.deviceId = deviceId;
            this.camera = deviceId;
            console.log("On Camera Change Event", deviceId);
        }
    }
}
</script>

<style scoped>

</style>
