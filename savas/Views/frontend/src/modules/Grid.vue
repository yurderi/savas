<template>
    <div class="grid">
        <v-grid-header v-model="searchInput"
                       :resultCount="filteredModels.length"
                       :buttons="opts.buttons"
                       @click="click">
        </v-grid-header>

        <div class="grid-items" :class="'column-' + opts.columns">
            <slot name="item" v-for="model in filteredModels" :model="model">
                {{ model }}
            </slot>
        </div>
    </div>
</template>

<script>
import _ from 'lodash'

export default {
    data: () => ({
        searchInput: '',
        models: [],
        isLoading: false,
        opts: {
            model: null,
            buttons: [
                {
                    name: 'create',
                    label: '',
                    icon: 'plus',
                    action: 'create'
                },
                {
                    name: 'refresh',
                    label: 'refresh',
                    icon: '',
                    action: 'load',
                    spin: false
                }
            ],
            columns: 1,
            fetchParams: () => ({})
        }
    }),
    props: {
        config: {
            required: false,
            type: Object
        }
    },
    computed: {
        $model () {
            return this.opts.model
        },
        filteredModels () {
            let me = this

            return me.$model.filter(me.models, me.searchInput)
        }
    },
    beforeMount() {
        let me = this

        me.opts = _.extend(me.opts, me.config)
    },
    mounted () {
        let me = this

        me.load()
    },
    methods: {
        click(action) {
            let me = this

            if (action in me && typeof me[action] === 'function') {
                me[action]()
            } else {
                me.$emit(action)
            }
        },
        load() {
            let me = this
            let button = me._getButton('refresh')
            let params = me.opts.fetchParams()

            me.isLoading = true
            button.spin  = true

            me.$model.list(params)
                .then(models => {
                    me.models = models

                    me.isLoading = false
                    button.spin  = false
                })

            me.$emit('load')
        },
        _getButton (name) {
            let me = this

            return me.opts.buttons.find(button => button.name === name)
        }
    }
}
</script>