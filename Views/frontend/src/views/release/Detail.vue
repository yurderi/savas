<template>
    <div class="is--release-detail">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>

            <div class="row" v-if="application && model">
                <div class="column" v-if="!isNew">
                    <ul class="sidebar-tabmenu--list">
                        <li :class="{ active: this.currentTab === 'details' }" @click="currentTab = 'details'">
                            Details
                        </li>
                        <li :class="{ active: this.currentTab === 'files' }" @click="currentTab = 'files'">
                            Files
                        </li>
                    </ul>
                </div>
                <div class="column flex" v-if="currentTab === 'details'">
                    <v-form
                        v-if="model" @submit="save"
                        :buttons="formButtons"
                    >
                        <div class="row">
                            <div class="column flex">
                                <div class="form-item">
                                    <v-checkbox v-model="model.active" label="Active"></v-checkbox>
                                </div>
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
                            </div>
                            <div class="column flex" style="margin-left: 20px">
                                <div class="form-item  column flex">
                                    <label for="description">
                                        Notes
                                    </label>
                                    <v-input
                                        type="textarea"
                                        id="description"
                                        v-model="model.description"
                                        class="column flex"
                                    ></v-input>
                                </div>
                            </div>
                        </div>
                    </v-form>
                </div>
                <div class="column flex" v-if="currentTab === 'files'">
                    <v-file-grid :application="application" :release="model"></v-file-grid>
                </div>
            </div>
        </v-content>
    </div>
</template>

<script>
    import async from 'async';
    import VFileGrid from '@/views/file/Grid';

    export default {
        components: {
            VFileGrid
        },
        data: () => (
            {
                loadedFn: () => {},
                currentTab: 'details',
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
            }
        ),
        computed: {
            id() {
                return this.$route.params.id;
            },
            isNew() {
                return !this.id;
            },
            applicationID() {
                return this.$route.params.applicationID;
            },
            $app() {
                return this.$models.application;
            },
            $release() {
                return this.$models.release;
            },
            $channel() {
                return this.$models.channel;
            },
            breadcrumb() {
                let me = this;

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
                            return 'edit ' + me.application.label;
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
                            return 'edit ' + me.model.version;
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
                ];
            }
        },
        watch: {
            id: {
                handler() {
                    let me = this;

                    if (me.isNew) {
                        me.model = me.$release.create();
                        me.loadedFn();
                    } else {
                        me.$release.get(this.id)
                            .then(response => {
                                me.model = response;
                                me.loadedFn();
                            });
                    }
                },
                immediate: true
            }
        },
        mounted() {
            let me = this;

            async.parallel([
                (done) => {
                    me.$app.get(me.applicationID)
                        .then(app => {
                            me.application = app;
                            done(null);
                        });
                },
                (done) => {
                    me.$channel.list()
                        .then(channels => {
                            me.channels = channels;
                            done(null);
                        });
                },
                (done) => {
                    me.loadedFn = done;
                }
            ], () => {
                me.channels.forEach(channel => {
                    if (channel.main) {
                        me.model.channelID = channel.id;
                    }
                });
            });
        },
        methods: {
            save({ setLoading, setMessage }) {
                let me = this;

                setMessage(null);
                setLoading(true);

                me.model.appID = me.applicationID;

                me.$release.save(me.model)
                    .then(response => {
                        if (response.success) {
                            setMessage('success', 'The release were saved successfully');

                            if (me.isNew) {
                                me.$router.push({
                                    name: 'release-edit',
                                    params: {
                                        applicationID: me.applicationID,
                                        id: response.data.id
                                    }
                                });
                            }
                        } else {
                            let error = typeof response.messages === 'object'
                                ? response.messages.join('<br />')
                                : response.message;

                            setMessage('error', error);
                        }
                    })
                    .catch(error => {
                        setMessage('error', error);
                    })
                    .finally(() => {
                        setLoading(false);
                    });
            }
        }
    };
</script>