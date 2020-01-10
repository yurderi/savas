<template>
    <div class="data-grid">
        <div class="grid-header row">
            <v-button class="left-button" v-if="opts.leftButton" @click="handleButton(opts.leftButton)">
                <fa :icon="opts.leftButton.icon"></fa>
            </v-button>
            <div class="pagination flex row center-x" v-if="opts.pagination">
                <v-button class="prev"
                          @click="paginatePrev"
                          :disabled="pagination.current <= 1">
                    <fa icon="arrow-left"></fa>
                </v-button>
                <div class="page-number">
                    {{ pagination.current }}
                    of
                    {{ pagination.pageCount }}
                </div>
                 <v-button class="next"
                           @click="paginateNext"
                           :disabled="pagination.current >= pagination.pageCount">
                    <fa icon="arrow-right"></fa>
                </v-button>
            </div>
            <v-button class="right-button" v-if="opts.rightButton" @click="handleButton(opts.rightButton)">
                <fa :icon="opts.rightButton.icon"></fa>
            </v-button>
        </div>
        <div class="grid-content">
            <div class="grid-row main row">
                <div class="grid-column" v-for="(column, key) in opts.columns" :key="key"
                     :style="{ width: column.width || 'auto', flex: column.flex === 1 ? '1' : 'unset' }">
                    <span>
                        {{ column.label }}
                    </span>
                </div>
                <div class="grid-column action-column" v-if="opts.actions && opts.actions.length > 0"
                     :style="{ width: (opts.actions.length * 50) + 'px' }">
                    &nbsp;
                </div>
            </div>
            <div class="grid-row row" v-for="(item, key) in items" :key="key"
                 :class="{ colored: key % 2 === 1 }">
                <div class="grid-column" v-for="(column, key) in opts.columns" :key="key"
                     :style="{ width: column.width || 'auto', flex: column.flex === 1 ? '1' : 'unset' }">
                    <a href="#" v-if="column.main" @click.prevent="$emit('open', item)">
                        {{ column.render ? column.render(item) : item[column.key] }}
                    </a>
                    <span v-else>
                        {{ column.render ? column.render(item) : item[column.key] }}
                    </span>
                </div>
                <div class="grid-column action-column" v-if="opts.actions && opts.actions.length > 0">
                    <v-button class="action" v-for="(action, key) in opts.actions" :key="key"
                         :class="'is-' + (action.color || 'black')"
                         @click="handleAction(action, item)">
                        <fa :icon="action.icon"></fa>
                    </v-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash'

/**
 * A yet simple but powerful data grid.
 */
const defaultConfig = {
    /**
     * Enable/disable pagination
     */
    pagination: true,
    /**
     * Items per page
     */
    itemLimit: 10,
    leftButton: {
        icon: 'plus',
        name: 'add'
    },
    rightButton: {
        icon: 'sync-alt',
        name: 'refresh'
    },
    model: null,
    /**
     * Example value:
     *
     * [
     *     {
     *         label: 'ID',
     *         key: 'id',
     *         width: '50px'
     *     }
     * ]
     */
    columns: [],
    /**
     * Example value:
     *
     * [
     *     {
     *         icon: 'trash',
     *         action: 'remove',
     *         color: 'red|green|blue|black'
     *     }
     * ]
     */
    actions: []
}

export default {
    name: 'v-data-grid',
    props: {
        config: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        opts: null,
        data: [],

        pagination: {
            current: 1,
            pageCount: 1
        }
    }),
    beforeMount() {
        let me = this

        me.opts = _.extend(defaultConfig, me.config)
    },
    mounted() {
        let me = this

        me.fetchData()
    },
    computed: {
        items () {
            let me = this
            let start = (me.pagination.current * me.opts.itemLimit) - me.opts.itemLimit
            let end = start + me.opts.itemLimit
            
            return me.data.slice(start, end)
        }
    },
    methods: {
        fetchData() {
            let me = this

            me.opts.model.list().then(data => {
                me.data = data
                
                me.pagination.pageCount = Math.max(1, Math.ceil(me.data.length / me.opts.itemLimit))
                
                if (me.pagination.current > me.pagination.pageCount) {
                    me.pagination.current = 1
                }
            })
        },
        paginateNext() {
            let me = this
            
            me.pagination.current++
        },
        paginatePrev() {
            let me = this

            me.pagination.current--
        },
        handleButton ({ icon, name }) {
            let me = this
            
            if (name === 'refresh') {
                me.fetchData()
            } else {
                me.$emit(name)
            }
        },
        handleAction ({ action }, item) {
            let me = this
            
            me.$emit(action, { grid: me, row: item })
        }
    }
}
</script>