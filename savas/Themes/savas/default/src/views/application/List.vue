<template>
    <div class="view is--application-list">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>

            <div class="grid-header">
                <div class="search">
                    <v-input type="text" placeholder="Filter applications..." v-model="filter" @keydown.esc="filter=''"></v-input>
                    <span class="search-result" v-if="filter.length > 0">
                        {{ filteredModels.length > 0 ? (filteredModels.length + ' results') : 'nothing found' }}
                    </span>
                </div>

                <div class="grid-controls">
                    <v-button :spin="isLoading" @click="load">refresh</v-button>
                </div>
            </div>

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
        filter: ''
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

            me.isLoading = true
            me.$model.list()
                .then(models => {
                    me.models = models
                    me.isLoading = false
                })
                .catch(error => {
                    // ???
                    console.log(error)
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