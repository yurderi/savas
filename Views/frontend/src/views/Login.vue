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
                <v-form @submit="submit" :buttons="form.buttons" message-position="bottom">
                    <div class="form-item">
                        <label for="username">
                            Username
                        </label>
                        <v-input type="text" id="username" v-model="form.username"></v-input>
                    </div>
                    <div class="form-item">
                        <label for="password">
                            Password
                        </label>
                        <v-input type="password" id="password" v-model="form.password"></v-input>
                    </div>
                </v-form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: () => (
            {
                form: {
                    username: '',
                    password: '',
                    register: false,

                    buttons: [
                        {
                            label: 'Submit',
                            primary: true,
                            name: 'submit'
                        }
                    ]
                }
            }
        ),
        methods: {
            submit({ setMessage, setLoading, setProgress }) {
                let me = this;

                setLoading(true);

                me.$http.post('frontend/user/login', me.form)
                    .then(response => response.data)
                    .then(response => {
                        if (response.success === true) {
                            me.$router.push('/');
                        } else {
                            setMessage('error', response.error);
                        }
                    })
                    .catch(error => {
                        setMessage('error', error);
                        console.log(error);
                    })
                    .finally(() => {
                        setLoading(false);
                    });
            }
        }
    };
</script>