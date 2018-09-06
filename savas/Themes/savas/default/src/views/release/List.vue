<template>
    <div class="is--release-list">
        <v-grid ref="grid" :config="gridConfig" @create="create" @load="load">
            <div class="grid-item release" slot="item" slot-scope="{ model }">
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
        </v-grid>
    </div>
</template>

<script>
import async from 'async'

export default {
    data() {
        return {
            channels: [],
            gridConfig: {
                model: this.$models.release,
                columns: 2,
                fetchParams: () => ({
                    applicationID: this.application.id
                })
            }
        }
    },
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


    },
    methods: {
        load () {
            let me = this

            me.$channel.list().then(channels => me.channels = channels)
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
                    me.$grid.load()
                })
        },
        _channel (id) {
            let me = this

            return me.channels.find(channel => channel.id === id) || me.$channel.create()
        }
    }
}
</script>