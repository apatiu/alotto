import {boot} from 'quasar/wrappers'
import axios from 'axios'

const guestToken = '7d337503ca244499291e1fc398ba27'

axios.defaults.headers.common['Cockpit-Token'] = guestToken;
const api = axios.create({baseURL: 'https://alotto.charoenchaigold.com/api'})

export default boot(({app}) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api

  app.config.globalProperties.$axios = axios
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
})

export {axios, api}
