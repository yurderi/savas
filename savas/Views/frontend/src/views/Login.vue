<template>
    <div class="is--login">
        
        <div class="login-container">
            <div class="login-header">
                <div class="header-logo">
                    <img src="../assets/img/logo.svg" />
                </div>
                <div class="header-text">
                    An http-service to serve updates for your applications
                </div>
            </div>
            <div class="login-form">
                <form @submit.prevent="submit">
                    <div class="form-item">
                        <label for="username">
                            Username
                        </label>
                        <v-input type="text" id="username" v-model="form.username" :disabled="loggingIn"></v-input>
                    </div>
                    <div class="form-item">
                        <label for="password">
                            Password
                        </label>
                        <v-input type="password" id="password" v-model="form.password" :disabled="loggingIn"></v-input>
                    </div>
                    <div class="form-buttons">
                        <v-button :spin="loggingIn">
                            Submit
                        </v-button>
                    </div>
                    <div class="form-result" v-if="result.errorMessage">
                        {{ result.errorMessage }}
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        loggingIn: false,
        form: {
            username: '',
            password: '',
            register: false
        },
        result: {
            errorMessage: null
        }
    }),
    methods: {
        submit () {
            let me = this

            me.loggingIn = true
            me.result.errorMessage = null
            
            me.$http.post('frontend/user/login', me.form)
                .then(response => response.data)
                .then(response => {
                    if (response.success === true) {
                        me.$router.push('/')
                    } else {
                        me.result.errorMessage = response.error
                    }
                })
                .catch(error => {
                    console.log(error)
                })
                .finally(() => {
                    me.loggingIn = false
                })
        }
    }
}
</script>