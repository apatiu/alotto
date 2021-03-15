<template>
    <label for="">น้ำหนักสินค้า</label>
    <div class="p-inputgroup">
        <Dropdown placeholder="น้ำหนัก" :options="options" v-if="value.bahtMode"
                  v-model="value.weight_baht"
                  :editable="true"
                  @update:modelValue="emit"></Dropdown>
        <InputNumber mode="decimal"
                     :minFractionDigits="1"
                     :maxFractionDigits="1"
                     placeholder="น้ำหนัก"
                     v-model="value.weight_gram" v-else></InputNumber>
        <Button :label="value.bahtMode ? 'บาท' : 'กรัม'"
                @click="value.bahtMode=!value.bahtMode"
                v-bind:class="classObject"></Button>
    </div>
</template>

<script>
    export default {
        name: "InputWeight",
        props: {
            'modelValue': {
                type: Object,
                default: {
                    weight_bath: null,
                    weight_gram: null,
                    bahtMode: true
                }
            }
        },
        data() {
            return {
                value: {
                    weight_baht: this.modelValue.weight_baht,
                    weight_gram: this.modelValue.weight_gram,
                    bahtMode: this.modelValue.bahtMode,
                },
                options: [0.125, 0.25, 0.5, 1, 2, 3, 5, 10]
            }
        },
        computed: {
            classObject: function () {
                return {
                    'p-button-warning': !this.bahtMode,
                }
            }
        },
        methods: {
            emit() {
                this.$emit('update:modelValue', this.value)
            }
        }
    }
</script>

<style scoped>

</style>
