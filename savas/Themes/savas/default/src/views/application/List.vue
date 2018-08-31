<template>
    <div class="view is--application-list">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>

            <v-grid-header v-model="filter"
                           :resultCount="filteredModels.length"
                           :buttons="gridButtons"
                           @create="create"
                           @refresh="load">
            </v-grid-header>

            <div class="application-items">
                <div class="application-item" v-for="model in filteredModels">
                    <div class="item-label">
                        {{ model.label }}
                    </div>
                    <div class="item-description">
                        {{ model.description }}
                    </div>
                    <div class="item-information">
                        <div class="information-item">
                            <div class="item-label">version</div>
                            <div class="item-value">1.0.0</div>
                        </div>
                        <div class="information-item">
                            <div class="item-label">releases</div>
                            <div class="item-value">1</div>
                        </div>
                        <div class="information-item">
                            <div class="item-label">downloads</div>
                            <div class="item-value">0</div>
                        </div>
                        <div class="information-item">
                            <div class="item-label">feedback</div>
                            <div class="item-value">0</div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <router-link :to="{ name: 'application-edit', params: { id: model.id } }">
                            <fa icon="pencil-alt"></fa>
                            edit
                        </router-link>
                        <a href="#" @click.prevent="remove(model)">
                            <fa icon="trash"></fa>
                            remove
                        </a>
                    </div>
                </div>
            </div>

        </v-content>
    </div>
</template>

<script>
export default {
    data: () => ({
        models: [],
        isLoading: false,
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
    computed: {
        breadcrumb() {
            return [
                {
                    label: 'applications',
                    route: { name: 'application-list' }
                }
            ]
        },
        filteredModels () {
            return this.$model.filter(this.models, this.filter)
        },
        $model() {
            return this.$models.application
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

            me.$model.list()
                .then(models => {
                    me.models = models
                    me.gridButtons[1].spin = false
                })
                .catch(error => {
                    // ???
                    console.log(error)
                })
        },
        create() {
            let me = this

            me.$router.push({
                name: 'application-create'
            })
        },
        remove(model) {
            let me = this

            me.$model.remove(model)
                .then(success => {
                    me.load()
                })
        }
    }
}
</script>