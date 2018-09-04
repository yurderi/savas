<template>
    <div class="is--release-list">
        <v-grid-header :buttons="gridButtons"
                       @create="create" @refresh="load"
                       v-model="filter" :resultCount="models.length">

        </v-grid-header>

        <div class="release-items">
            <div class="release-item" v-for="model in models">
                <div class="item-label">
                    {{ model.version }}
                </div>
                <div class="item-description">
                    {{ model.description }}
                </div>
                <div class="item-channel">
                    {{ _channel(model.channelID).label }}
                </div>
                <div class="item-actions">
                    <a href="#" @click.prevent="edit(model)">
                        <fa icon="pencil-alt"></fa>
                    </a>
                    <a href="#" @click.prevent="remove(model)">
                        <fa icon="trash"></fa>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import async from 'async'

export default {
    data: () => ({
        models: [],
        channels: [],
        filter: '',
        gridButtons: [
            {
                name: 'create',
                icon: 'plus'
            },
            {
                label: 'refresh',
                name: 'refresh',
                spin: false
            }
        ]
    }),
    props: {
        application: {
            type: Object,
            required: true
        }
    },
    computed: {
        $model() {
            return this.$models.release
        },
        $channel() {
            return this.$models.channel
        }
    },
    mounted() {
        let me = this

        me.load()
    },
    methods: {
        load() {
            let me = this

            me.gridButtons[1].spin = true

            async.series([
                (done) => {
                    me.$channel.list()
                        .then(channels => me.channels = channels)
                        .finally(() => {
                            done(null)
                        })
                },
                (done) => {
                    me.$model.list({ applicationID: me.application.id })
                        .then(models => me.models = models)
                        .finally(() => {
                            done(null)
                        })
                }
            ], () => {
                me.gridButtons[1].spin = false
            })
        },
        create () {
            let me = this

            me.$router.push({
                name: 'release-create',
                params: {
                    applicationID: me.application.id
                }
            })
        },
        edit (model) {
            let me = this

            me.$router.push({
                name: 'release-edit',
                params: {
                    applicationID: me.application.id,
                    id: model.id
                }
            })
        },
        remove (model) {
            let me = this

            me.$model.remove(model)
                .then(() => {
                    me.load()
                })
        },
        _channel (id) {
            let me = this

            return me.channels.find(channel => channel.id === id)
        }
    }
}
</script>