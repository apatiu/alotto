<template>
  <q-input
    :model-value="displayValue"
    :label="label"
    :type="timePicker ? 'time' : 'text'">
    <q-popup-proxy v-model="popup" v-if="!timePicker">
      <q-date :model-value="value"
              @update:model-value="onUpdateValue"
              :mask="valueFormat"
              emit-immediately></q-date>
    </q-popup-proxy>
  </q-input>
</template>

<script>
import {date} from 'quasar'

export default {
  name: "ADatePicker",
  inheritAttrs: false,
  props: {
    modelValue: {default: null},
    label: {type: String},
    displayFormat: {type: String, default: 'DD/MM/YYYY'},
    valueFormat: {type: String, default: 'YYYY/MM/DD HH:mm'},
    timePicker: {type: Boolean, default: false}
  },
  data: () => ({
    value: null,
    popup: false
  }),
  computed: {
    displayValue() {
      if (this.timePicker)
        return date.formatDate(this.modelValue, 'HH:mm')
      return date.formatDate(this.modelValue, this.displayFormat)
    }
  },
  mounted() {
    this.value = this.modelValue
  },
  methods: {
    onUpdateValue(value, reason, details) {
      this.popup = false
      this.value = value
      this.$emit('update:model-value', value);
    }
  }
}
</script>

<style scoped>

</style>
