<template>
    <div class="is--release-list">
        <v-grid ref="grid" :config="gridConfig" @create="create" @load="load">
            <div class="grid-item release" slot="item" slot-scope="{ model }">
                <div class="item-label">
                    v{{ model.version }}
                </div>
                <div class="item-description" v-html="$md.render(model.description)"></div>
                <div class="item-meta">
                    <div class="meta-item">
                        <fa icon="clock"></fa>
                        {{ $moment(model.created).fromNow() }}
                        &CenterDot;
                        {{ $moment(model.created).format('DD.MM.YYYY') }}
                    </div>
                    <div class="meta-item">
                        <fa icon="tag"></fa>
                        {{ _channel(model.channelID).label }}
                    </div>
                    <div class="meta-item" v-if="model.files > 0">
                        <fa icon="file"></fa>
                        {{ model.files }}
                    </div>
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
                columns: 1,
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
        },
        $grid() {
            return this.$refs.grid
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