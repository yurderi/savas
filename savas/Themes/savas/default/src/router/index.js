import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'index',
            component: require('@/views/Index').default
        },
        {
            path: '/login',
            name: 'login',
            component: require('@/views/Login').default
        }
    ]
})
