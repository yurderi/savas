<template>
    <div class="header-container">
        <div class="header">
            <div class="header-title">
                <span>savas</span>
                <small>2.0</small>
            </div>
            <ul class="header-menu">
                <li>
                    <router-link to="/">
                        <fa icon="home"></fa>
                    </router-link>
                </li>
                <li>
                    <router-link to="/application/list">applications</router-link>
                    <ul class="sub-menu">
                        <li>
                            <router-link to="/application/create">create</router-link>
                        </li>
                        <li>
                            <router-link to="/application/settings">settings</router-link>
                        </li>
                    </ul>
                </li>
                <li @click="logout">
                    <a href="#">
                        <fa icon="sign-out-alt"></fa>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        logout () {
            let me = this

            me.$http.get('user/logout')
                .then(response => response.data)
                .then(response => {
                    me.$router.push('/login')
                })
        }
    }
}
</script>