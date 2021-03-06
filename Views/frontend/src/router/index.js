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
        },
        {
            path: '/application/list',
            name: 'application-list',
            component: require('@/views/application/List').default
        },
        {
            path: '/application/settings',
            name: 'application-settings',
            component: require('@/views/application/Settings').default
        },
        {
            path: '/application/create',
            name: 'application-create',
            component: require('@/views/application/Detail').default
        },
        {
            path: '/application/:id',
            name: 'application-edit',
            component: require('@/views/application/Detail').default
        },
        {
            path: '/application/:applicationID/release/create',
            name: 'release-create',
            component: require('@/views/release/Detail').default
        },
        {
            path: '/application/:applicationID/release/:id',
            name: 'release-edit',
            component: require('@/views/release/Detail').default
        }
    ]
})
