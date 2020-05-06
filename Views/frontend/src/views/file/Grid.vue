<template>
    <div>
        <v-data-grid :config="gridConfig" ref="grid" @add="create" @open="edit" @remove="remove" @download="download" />

        <v-modal v-if="file" class="file-form">
            <div class="modal-header">
                <template v-if="file.id > 0">
                    Edit file
                </template>
                <template v-else>
                    Create file
                </template>
            </div>

            <v-form @submit="submit" :buttons="formButtons" @abort="abort">
                <div class="form-item platform">
                    <label for="platform">
                        Platform
                    </label>
                    <v-select :data="platforms" v-model="file.platformID" id="platform"></v-select>
                </div>
                <div class="row">
                    <div class="form-item">
                        <label for="file">
                            File
                        </label>
                        <v-file id="file" v-model="file.displayName" ref="file"></v-file>
                    </div>
                    <div class="form-item">
                        <label for="size">
                            Size
                        </label>
                        <v-input type="text" :value="bytes(file.size)" readonly />
                    </div>
                </div>
                <v-requirements :file="file" />
            </v-form>
        </v-modal>
    </div>
</template>

<script>
    import bytes from 'bytes';
    import VRequirements from './Requirements'

    export default {
        components: {
            VRequirements
        },
        props: {
            application: {
                required: true,
                type: Object
            },
            release: {
                required: true,
                type: Object
            }
        },
        data() {
            return {
                bytes,
                gridConfig: {
                    itemLimit: 10,
                    model: this.$models.file,
                    extraParams: {
                        releaseID: this.release.id
                    },
                    columns: [
                        {
                            label: 'Filename',
                            key: 'displayName',
                            flex: 1,
                            main: true
                        },
                        {
                            label: 'Size',
                            key: 'size',
                            width: '100px',
                            render(model) {
                                return bytes(model.size, { unitSeparator: ' ' });
                            }
                        },
                        {
                            label: 'Platform',
                            key: 'platformID',
                            width: '150px',
                            render: (model) => {
                                return this.platform(model.platformID).label;
                            }
                        }
                    ],
                    actions: [
                        {
                            icon: 'download',
                            action: 'download',
                            color: 'black'
                        },
                        {
                            icon: 'trash',
                            action: 'remove',
                            color: 'red'
                        }
                    ]
                },
                platforms: [],
                file: null,
                formButtons: [
                    {
                        label: 'Abort',
                        name: 'abort',
                        class: 'secondary'
                    },
                    {
                        label: 'Submit',
                        primary: true,
                        name: 'submit'
                    }
                ]
            };
        },
        computed: {
            $platform() {
                return this.$models.platform;
            },
            $model() {
                return this.$models.file;
            },
            $grid() {
                return this.$refs.grid
            }
        },
        mounted() {
            var me = this;

            me.$platform.list().then(platforms => me.platforms = platforms);
        },
        methods: {
            create() {
                let me = this;

                me.file = me.$model.create();
                me.file.releaseID = me.release.id;
            },
            download({ row: model }) {
                let me  = this;
                let url = me.$http.defaults.baseURL
                    + 'frontend/file/download?id='
                    + model.id
                    + '&filename='
                    + model.displayName;

                window.open(url, '_blank');
            },
            edit(model) {
                let me = this;

                me.file = model;
            },
            remove({ grid, row }) {
                let me = this;

                me.$models.file.remove(row).then(() => {
                    grid.fetchData();
                });
            },
            submit({ setLoading, setMessage, setProgress }) {
                let me     = this;
                let data   = new FormData();
                let config = {
                    onUploadProgress: function(progressEvent) {
                        let percentCompleted = Math.round((
                            progressEvent.loaded * 100
                        ) / progressEvent.total);

                        setProgress(percentCompleted, 'Progressing... ' + percentCompleted + '%');
                    }
                };

                data.append('id', me.file.id);
                data.append('displayName', me.file.displayName);
                data.append('releaseID', me.file.releaseID);
                data.append('platformID', me.file.platformID);
                data.append('file', me.$refs.file.$refs.fileInput.files[0]);

                setMessage(null);
                setProgress(0, 'Progressing...');

                me.$http.post('frontend/file/save', data, config).then(response => response.data).then(response => {
                    if (response.success) {
                        me.file = null;
                        me.$grid.fetchData();
                    } else {
                        if (typeof response === 'object') {
                            throw response.messages.join('<br />');
                        }

                        throw 'The file could not be saved.'
                    }
                }).catch(error => {
                    if (error instanceof Error) {
                        setMessage('error', error.message);
                    } else {
                        setMessage('error', error);
                    }
                }).finally(() => {
                    setLoading(false);
                    setProgress(null);
                });
            },
            abort() {
                let me = this;

                me.file = null;
            },
            platform(id) {
                let me = this;

                return me.platforms.find(platform => platform.id === id) || me.$platform.create();
            }
        }
    };
</script>