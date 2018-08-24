import Vue from 'vue'
import App from './App'
import router from './router'
import VueUI from '@yurderi/vue-ui/dist/vue-ui.js'
import '@yurderi/vue-ui/dist/vue-ui.css'
import '@/assets/less/all.less'
import axios from 'axios'

Vue.config.productionTip = false

Vue.use(VueUI)

Vue.http = Vue.prototype.$http = axios.create({
    baseURL: 'savas'
})

new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
