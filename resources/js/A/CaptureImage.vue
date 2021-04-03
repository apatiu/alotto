<template>
    <webcam
        ref="webcam"
        :device-id="deviceId"
        width="640"
        height="480"
        @started="onStarted"
        @stopped="onStopped"
        @error="onError"
        @cameras="onCameras"
        @camera-change="onCameraChange"
    />

    <div class="p-grid p-mt-2">

        <div class="p-md-9">
            <select v-model="camera">
                <option>-- Select Device --</option>
                <option
                    v-for="device in devices"
                    :key="device.deviceId"
                    :value="device.deviceId"
                >{{ device.label }}
                </option>
            </select>
        </div>
        <div class="p-md-3">
            <Button type="button" class="btn btn-primary" @click="onCapture">จับภาพ</Button>
            <!--                        <Button type="button" class="btn btn-danger" @click="onStop">Stop Camera</Button>-->
            <!--                        <Button type="button" class="btn btn-success" @click="onStart">Start Camera</Button>-->
        </div>
    </div>
</template>

<script>
import Webcam from "@/A/Webcam";

export default {
    name: "CaptureImage",
    components: {Webcam},
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
            this.onStop();
            this.$emit('captured',this.img);

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
