<template>
    <label for="">น้ำหนัก</label>
    <div class="p-inputgroup">
        <Dropdown :options="options"
                  v-model="value.weight"
                  @update:modelValue="emit"
                  :editable="true"></Dropdown>
        <Button :label="buttonLabel"
                @click="value.weightbaht=!value.weightbaht"
                v-bind:class="classObject"></Button>
    </div>
</template>

<script>
export default {
    name: "InputWeight",
    props: {
        modelValue: {
            type: Object,
            default: {weight: 0, weightbaht: true}
        }
    },
    data() {
        return {
            value: {
                weight: this.modelValue.weight,
                weightbaht: this.modelValue.weightbaht
            },
            options: [0.125, 0.25, 0.5, 1, 2, 3, 5, 10]
        }
    },
    watch: {
        value: {
            handler(val) {
                this.emit()
            },
            deep: true
        }

    },
    computed: {
        classObject: function () {
            return {
                'p-button-warning': !this.weightbaht,
            }
        },
        buttonLabel() {
            return this.value.weightbaht ? 'บาท' : 'กรัม'
        }
    },
    methods: {
        emit() {
            console.log(this.value);
            this.$emit('update:modelValue', {weight: this.value.weight, weightbaht: this.value.weightbaht})
        }
    }
}
</script>

<style scoped>

</style>
