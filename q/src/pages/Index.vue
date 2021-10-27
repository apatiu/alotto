<template>
  <q-page padding class="flex-center flex">
    <div>
      <div class="row">
        ADatePicker
      </div>
      <p>{{ dt }}</p>
      <div class="row">
        <a-date-picker v-model="dt"
                       label="วันที่"
        />
      </div>
    </div>
  </q-page>
</template>

<script>
import {defineComponent} from 'vue';
import ADatePicker from "components/ADatePicker";

export default defineComponent({
  name: 'PageIndex',
  components: {ADatePicker},
  data: () => ({
    dt: new Date().toISOString()
  })
})
</script>
