<template>
  <q-page class="flex flex-center">

  </q-page>
</template>

<script>
import { defineComponent } from 'vue';
import { NFC } from 'nfc-pcsc'

export default defineComponent({
  name: 'PageIndex',
  data: () => ({
    nfc: new NFC()
  })
})
</script>
