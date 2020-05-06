<template>
    <div class="is--release-list">
        <v-data-grid :config="gridConfig" @add="create" @open="edit" @remove="remove"></v-data-grid>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                channels: [],
                gridConfig: {
                    itemLimit: 10,
                    model: this.$models.release,
                    extraParams: {
                        applicationID: this.application.id
                    },
                    columns: [
                        {
                            label: 'Active',
                            key: 'active',
                            width: '100px',
                            render(row) {
                                return row.active ? 'Yes' : 'No';
                            }
                        },
                        {
                            label: 'Version',
                            key: 'version',
                            width: '100px',
                            main: true
                        },
                        {
                            label: 'Channel',
                            key: 'channel',
                            width: '100px',
                            render: (model) => {
                                let channel = this.channels.find(c => c.id === model.channelID)
                                    || this.$models.channel.create();

                                return channel.label;
                            }
                        },
                        {
                            label: 'Files',
                            key: 'files',
                            width: '80px'
                        },
                        {
                            label: 'Notes',
                            key: 'description',
                            flex: 1,
                            raw: true,
                            render(row) {
                                return row.description || '<p style="color:#aaaaaa">empty</p>';
                            }
                        }
                    ],
                    actions: [
                        {
                            icon: 'trash',
                            action: 'remove',
                            color: 'red'
                        }
                    ]
                }
            };
        },
        props: {
            application: {
                type: Object,
                required: true
            }
        },
        computed: {
            $model() {
                return this.$models.release;
            },
            $channel() {
                return this.$models.channel;
            }
        },
        mounted() {
            this.load();
        },
        methods: {
            load() {
                let me = this;

                me.$channel.list().then(channels => me.channels = channels);
            },
            create() {
                let me = this;

                me.$router.push({
                    name: 'release-create',
                    params: {
                        applicationID: me.application.id
                    }
                });
            },
            edit(model) {
                let me = this;

                me.$router.push({
                    name: 'release-edit',
                    params: {
                        applicationID: me.application.id,
                        id: model.id
                    }
                });
            },
            remove({ grid, row }) {
                let me = this;

                me.$model.remove(row)
                    .then(() => {
                        grid.fetchData();
                    });
            },
            _channel(id) {
                let me = this;

                return me.channels.find(channel => channel.id === id) || me.$channel.create();
            }
        }
    };
</script>