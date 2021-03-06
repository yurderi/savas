<template>
    <div class="view is--application-detail">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>

            <div class="row">
                <div class="column" v-if="!isNew">
                    <ul class="sidebar-tabmenu--list">
                        <li :class="{ active: this.currentTab === 'details' }" @click="currentTab = 'details'">
                            Details
                        </li>
                        <li :class="{ active: this.currentTab === 'releases' }" @click="currentTab = 'releases'">
                            Releases
                        </li>
                    </ul>
                </div>
                <div class="column flex" v-if="this.currentTab === 'details'">
                    <v-form v-if="model" @submit="save" :buttons="formButtons">
                        <div class="row">
                            <div class="column flex">
                                <div class="form-item">
                                    <label for="label">
                                        Label
                                    </label>
                                    <v-input
                                        type="text"
                                        id="label"
                                        v-model="model.label"
                                        @validate="validateLabel"
                                    ></v-input>
                                </div>
                                <div class="form-item">
                                    <label for="visibility">
                                        Visibility
                                    </label>
                                    <v-select
                                        :data="visibilityValues" v-model="model.visibility"
                                        displayField="value" valueField="id" id="visibility"
                                    >
                                    </v-select>
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
                            </div>
                            <div class="column flex" style="margin-left: 20px">
                                <div class="form-item column flex">
                                    <label for="description">
                                        Description
                                    </label>
                                    <v-input type="textarea" id="description" v-model="model.description" class="column flex"></v-input>
                                </div>
                            </div>
                        </div>
                    </v-form>
                </div>
                <div class="column flex" v-if="this.currentTab === 'releases'">
                    <v-releases-list :application="model" />
                </div>
            </div>
        </v-content>
    </div>
</template>

<script>
    import VReleasesList from '@/views/release/List';

    export default {
        components: {
            VReleasesList
        },
        data: () => (
            {
                model: null,
                formButtons: [
                    {
                        label: 'Submit',
                        name: 'submit',
                        primary: true
                    }
                ],
                visibilityValues: [
                    { id: 'public', value: 'public' },
                    { id: 'private', value: 'private' }
                ],
                currentTab: 'details'
            }
        ),
        computed: {
            id() {
                return this.$route.params.id;
            },
            isNew() {
                return !this.id;
            },
            breadcrumb() {
                return [
                    {
                        label: 'Applications',
                        route: { name: 'application-list' }
                    },
                    {
                        label: 'Create',
                        route: { name: 'application-create' },
                        active: this.isNew
                    },
                    {
                        label: () => {
                            return 'Edit "' + this.model.label + '"';
                        },
                        route: { name: 'application-edit' },
                        active: !this.isNew && this.model !== null
                    }
                ];
            },
            $model() {
                return this.$models.application;
            }
        },
        watch: {
            id: {
                handler() {
                    let me = this;

                    if (me.isNew) {
                        me.model = me.$model.create();
                    } else {
                        me.$model.get(this.id)
                            .then(response => {
                                me.model = response;
                            });
                    }
                },
                immediate: true
            }
        },
        methods: {
            save({ setLoading, setMessage }) {
                let me = this;

                setMessage(null);
                setLoading(true);

                me.$model.save(me.model)
                    .then(response => {
                        if (response.success) {
                            setMessage('success', 'The application was saved successfully');

                            if (me.isNew) {
                                me.$router.push({ name: 'application-edit', params: { id: response.data.id } });
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
            },
            validateLabel({ ok, fail, spin, clear }) {
                let me = this;

                spin();

                me.$http.get('frontend/application/checkLabel', { params: me.model })
                    .then(response => response.data)
                    .then(response => {
                        if (response.used === true) {
                            fail('The application name is already used by another user.');
                        } else {
                            ok();
                        }
                    })
                    .catch(error => {
                        fail('API not available');
                        throw error;
                    });
            }
        }
    };
</script>