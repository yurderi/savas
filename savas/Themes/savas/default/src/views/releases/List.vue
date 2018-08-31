<template>
    <div class="is--releases-list">
        <v-grid-header :buttons="gridButtons"
                       @create="create" @refresh="load"
                       v-model="filter" :resultCount="models.length">

        </v-grid-header>

        {{ models }}
    </div>
</template>

<script>
export default {
    data: () => ({
        models: [],
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

            me.$model.list({ applicationID: me.application.id })
                .then(models => me.models = models)
                .finally(() => {
                    me.gridButtons[1].spin = false
                })
        },
        create () {

        }
    }
}
</script>