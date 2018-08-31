<template>
    <div class="view is--application-detail">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>

            <v-tab-menu>
                <v-tab id="detail" label="Details">
                    <v-message v-if="result.type !== null" :type="result.type">
                        {{ result.message }}
                    </v-message>

                    <v-form v-if="model" @submit.prevent="save">
                        <div class="form-item">
                            <label for="label">
                                Label
                            </label>
                            <v-input type="text" id="label" v-model="model.label"></v-input>
                        </div>
                        <div class="form-item">
                            <label for="description">
                                Description
                            </label>
                            <v-input type="textarea" id="description" v-model="model.description"></v-input>
                        </div>
                        <div class="form-item" v-if="!isNew">
                            <label for="publicKey">
                                Public Key
                            </label>
                            <v-input type="text" id="publicKey" v-model="model.publicKey" readonly></v-input>
                        </div>
                        <div class="form-item" v-if="!isNew">
                            <label for="privateKey">
                                Private Key
                            </label>
                            <v-input type="text" id="privateKey" v-model="model.privateKey" readonly></v-input>
                        </div>

                        <div class="form-buttons">
                            <v-button :spin="result.loading">Submit</v-button>
                        </div>
                    </v-form>
                </v-tab>
            </v-tab-menu>
        </v-content>
    </div>
</template>

<script>
export default {
    data: () => ({
        model: null,
        result: {
            loading: false,
            type: null,
            message: null
        }
    }),
    computed: {
        id () {
            return this.$route.params.id
        },
        isNew() {
            return !this.id
        },
        breadcrumb() {
            return [
                {
                    label: 'applications',
                    route: { name: 'application-list' }
                },
                {
                    label: 'create',
                    route: { name: 'application-create' },
                    active: this.isNew
                },
                {
                    label: () => {
                        return 'edit ' + this.model.label
                    },
                    route: { name: 'application-edit' },
                    active: !this.isNew && this.model !== null
                }
            ]
        },
        $model() {
            return this.$models.application
        }
    },
    watch: {
        id: {
            handler () {
                let me = this

                if (me.isNew) {
                    me.model = me.$model.create()
                } else {
                    me.$model.get(this.id)
                        .then(response => {
                            me.model = response
                        })
                }
            },
            immediate: true
        }
    },
    methods: {
        save () {
            let me = this

            if (me.result.loading === true) {
                return
            }

            me.result.type    = me.result.message = null
            me.result.loading = true

            me.$model.save(me.model)
                .then(response => {
                    if (response.success) {
                        me.result.type    = 'success'
                        me.result.message = 'The application were saved successfully'

                        if (me.isNew) {
                            me.$router.push({ name: 'application-edit', params: { id: response.data.id }})
                        }
                    } else {
                        me.result.type    = 'error'
                        me.result.message = typeof response.messages === 'object'
                            ? response.messages.join('<br />')
                            : response.message
                    }
                })
                .catch(error => {
                    me.result.type    = 'error'
                    me.result.message = 'Unable to save the application. See console for more information.'
                })
                .finally(() => {
                    me.result.loading = false
                })
        }
    }
}
</script>