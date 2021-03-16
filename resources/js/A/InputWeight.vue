<template>
    <div class="p-inputgroup">
        <span class="p-float-label">
        <Dropdown :options="options"
                  v-model="value.weight"
                  :editable="true"></Dropdown>
            <label for="">น้ำหนัก</label>
        </span>
        <Button :label="value.weightbaht ? 'บาท' : 'กรัม'"
                @click="value.weightbaht=!value.weightbaht"
                v-bind:class="classObject"></Button>
    </div>


</template>

<script>
export default {
    name: "InputWeight",
    props: {
        'modelValue': {
            type: Array,
            default: [null, true]
        }
    },
    data() {
        return {
            value: {
                weight: this.modelValue[0],
                weightbaht: this.modelValue[1]
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
        }
    },
    methods: {
        emit() {
            this.$emit('update:modelValue', [this.value.weight, this.value.weightbaht])
        }
    }
}
</script>

<style scoped>

</style>
