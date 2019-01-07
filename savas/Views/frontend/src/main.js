import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import models from './models'
import VueUI from '@yurderi/vue-ui/src/index'
import '@yurderi/vue-ui/dist/vue-ui.css'
import '@/assets/less/all.less'
import axios from 'axios'

Vue.config.productionTip = false

Vue.use(VueUI)

Vue.http = Vue.prototype.$http = axios.create({
    baseURL: process.env.BASE_URL
})

Vue.prototype.$moment = require('moment')
Vue.prototype.$md     = require('markdown-it')()

Vue.models = Vue.prototype.$models = models

Vue.component('v-header', require('@/modules/Header.vue').default)
Vue.component('v-content', require('@/modules/Content.vue').default)
Vue.component('v-breadcrumb', require('@/modules/Breadcrumb.vue').default)
Vue.component('v-form', require('@/modules/Form.vue').default)
Vue.component('v-message', require('@/modules/Message.vue').default)
Vue.component('v-grid-header', require('@/modules/GridHeader.vue').default)
Vue.component('v-grid', require('@/modules/Grid.vue').default)
Vue.component('v-modal-form', require('@/modules/ModalForm.vue').default)

window.app = new Vue({
    el: '#app',
    router,
    store,
    components: {App},
    template: '<App/>'
})
