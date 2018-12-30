<template>
    <div id="app">
        <router-view v-if="isReady" />
        <div class="loader" v-else>
            <fa icon="spinner" spin></fa>
        </div>
    </div>
</template>

<script>
export default {
    name: 'App',
    data: () => ({
        isReady: false
    }),
    mounted () {
        let me = this

        me.$http.get('frontend/user/status')
            .then(response => response.data)
            .then(response => {
                if (response.loggedIn === true) {

                } else {
                    me.$router.push('/login')
                }

                me.isReady = true
            })
    }
}
</script>