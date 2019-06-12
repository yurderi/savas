<template>
    <div class="header-container">
        <div class="header">
            <div class="header-logo">
                <img src="../assets/img/logo.svg" />
            </div>
            <div class="header-search">
                <v-input type="text" v-model="search" placeholder="Search apps..."></v-input>
                <fa icon="search"></fa>
            </div>
            <div class="header-space">
            
            </div>
            <ul class="header-menu">
                <li class="is-icon">
                    <router-link to="/">
                        <fa icon="home"></fa>
                    </router-link>
                </li>
                <li class="has-children">
                    <router-link to="/application/list">Applications</router-link>
                    <ul class="sub-menu">
                        <li>
                            <router-link to="/application/create">Create</router-link>
                        </li>
                        <li>
                            <router-link to="/application/settings">Settings</router-link>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        Documentation
                    </a>
                </li>
                <li @click="logout" class="is-icon">
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
    data: () => ({
        search: ''
    }),
    methods: {
        logout () {
            let me = this

            me.$http.get('frontend/user/logout')
                .then(response => response.data)
                .then(response => {
                    me.$router.push('/login')
                })
        }
    }
}
</script>