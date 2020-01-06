<template>
    <div class="view is--application-list">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>
            <v-data-grid :config="gridConfig" @open="edit" @remove="remove"></v-data-grid>
        </v-content>
    </div>
</template>

<script>
export default {
    data() {
        return {
            gridConfig: {
                itemLimit: 10,
                model: this.$models.application,
                columns: [
                    {
                        label: 'Name',
                        key: 'label',
                        width: '200px',
                        main: true
                    },
                    {
                        label: 'Current Version',
                        key: 'currentVersion',
                        width: '200px',
                        render (row) {
                            return row.currentVersion === 'null' ? '-' : row.currentVersion
                        }
                    },
                    {
                        label: 'Description',
                        key: 'description',
                        flex: 1,
                        render (row) {
                            return row.description || '-'
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
        }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: 'Applications',
                    route: { name: 'application-list' }
                }
            ]
        },
        $model() {
            return this.$models.application
        },
        $grid() {
            return this.$refs.grid
        }
    },
    methods: {
        create() {
            let me = this

            me.$router.push({
                name: 'application-create'
            })
        },
        edit (model) {
            let me = this

            me.$router.push({
                name: 'application-edit',
                params: {
                    id: model.id
                }
            })
        },
        remove({ grid, row }) {
            let me = this
            
            me.$model.remove(row).then(() => {
                grid.fetchData()
            })
        }
    }
}
</script>