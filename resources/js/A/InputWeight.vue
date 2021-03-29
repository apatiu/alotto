<template>
    <label for="">น้ำหนัก</label>
    <div class="p-inputgroup">
        <Dropdown :options="options"
                  :modelValue="value.weight"
                  @input="emit"
                  :editable="true"></Dropdown>
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
            type: [Array, Number],
            default: [null, true]
        },
        returnGram: {type: Boolean, default: false}
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
        modelValue(val) {
            if (_.isArray(val)) {
                this.value = {
                    weight: val[0],
                    weightbaht: val[1]
                }
            } else {
                this.value = {
                    weight: val,
                    weightbaht: false
                }
            }
        },
        value(val) {
            this.emit()
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
            if (this.returnGram) {
                if (this.value.weightbaht) {
                    this.$emit('update:modelValue', this.value.weight * 15.2)
                } else {
                    console.log(this.value.weight);
                    this.$emit('update:modelValue', this.value.weight)
                }
            } else {
                this.$emit('update:modelValue', [this.value.weight, this.value.weightbaht])
            }
        }
    }
}
</script>

<style scoped>

</style>
