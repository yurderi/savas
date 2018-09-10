<template>
    <div class="is--release-detail">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>

            <v-tab-menu>
                <v-tab id="form" label="Details">
                    <v-form v-if="model" @submit="save"
                            :buttons="formButtons">
                        <div class="form-item">
                            <label for="channel">
                                Channel
                            </label>
                            <v-select :data="channels" v-model="model.channelID"></v-select>
                        </div>
                        <div class="form-item">
                            <label for="version">
                                Version
                            </label>
                            <v-input type="text" id="version" v-model="model.version"></v-input>
                        </div>
                        <div class="form-item">
                            <label for="description">
                                Description
                            </label>
                            <v-input type="textarea" id="description" v-model="model.description"></v-input>
                        </div>
                    </v-form>
                </v-tab>
                <v-tab id="files" label="Files" v-if="application && model && !isNew">
                    <v-file-grid :application="application" :release="model"></v-file-grid>
                </v-tab>
            </v-tab-menu>
        </v-content>
    </div>
</template>

<script>
import async from 'async'
import VFileGrid from '@/views/file/Grid'

export default {
    components: {
        VFileGrid
    },
    data: () => ({
        application: null,
        model: null,
        channels: [],
        formButtons: [
            {
                label: 'Submit',
                name: 'submit',
                primary: true
            }
        ]
    }),
    computed: {
        id () {
            return this.$route.params.id
        },
        isNew() {
            return !this.id
        },
        applicationID () {
            return this.$route.params.applicationID
        },
        $app() {
            return this.$models.application
        },
        $release() {
            return this.$models.release
        },
        $channel() {
            return this.$models.channel
        },
        breadcrumb () {
            let me = this

            return [
                {
                    label: 'application',
                    route: {
                        name: 'application-list'
                    }
                },
                {
                    label: 'EDIT <loading>',
                    route: {
                        name: 'application-edit',
                        params: {
                            id: me.applicationID
                        }
                    },
                    active: !me.application
                },
                {
                    label() {
                        return 'edit ' + me.application.label
                    },
                    route: {
                        name: 'application-edit',
                        params: {
                            id: me.applicationID
                        }
                    },
                    active: !!me.application
                },
                {
                    label: 'create release',
                    route: {
                        name: 'release-create',
                        params: {
                            applicationID: me.applicationID
                        }
                    },
                    active: me.isNew
                },
                {
                    label() {
                        return 'edit ' + me.model.version
                    },
                    route: {
                        name: 'release-edit',
                        params: {
                            applicationID: me.applicationID,
                            id: me.id
                        }
                    },
                    active: !me.isNew && me.model !== null
                }
            ]
        }
    },
    watch: {
        id: {
            handler () {
                let me = this

                if (me.isNew) {
                    me.model = me.$release.create()
                } else {
                    me.$release.get(this.id)
                        .then(response => {
                            me.model = response
                        })
                }
            },
            immediate: true
        }
    },
    mounted () {
        let me = this

        async.parallel([
            (done) => {
                me.$app.get(me.applicationID)
                    .then(app => {
                        me.application = app
                        done(null)
                    })
            },
            (done) => {
                me.$channel.list()
                    .then(channels => {
                        me.channels = channels
                        done(null)
                    })
            }
        ])
    },
    methods: {
        save ({ setLoading, setMessage }) {
            let me = this

            setMessage(null)
            setLoading(true)

            me.model.appID = me.applicationID

            me.$release.save(me.model)
                .then(response => {
                    if (response.success) {
                        setMessage('success', 'The release were saved successfully')

                        if (me.isNew) {
                            me.$router.push({
                                name: 'release-edit',
                                params: {
                                    applicationID: me.applicationID,
                                    id: response.data.id
                                }
                            })
                        }
                    } else {
                        let error = typeof response.messages === 'object'
                            ? response.messages.join('<br />')
                            : response.message

                        setMessage('error', error)
                    }
                })
                .catch(error => {
                    setMessage('error', error)
                })
                .finally(() => {
                    setLoading(false)
                })
        }
    }
}
</script>