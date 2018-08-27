<template>
    <div class="view is--application-list">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>

            <ul class="grid-controls">
                <li>
                    <v-button :spin="isLoading" @click="load">refresh</v-button>
                </li>
            </ul>

            <div class="application-items">
                <div class="application-item" v-for="model in models">
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
        isLoading: false
    }),
    computed: {
        breadcrumb() {
            return [
                {
                    label: 'applications',
                    route: { name: 'application-list' }
                }
            ]
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
            me.$models.application.list()
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

            me.$models.application.remove(model)
                .then(success => {
                    me.load()
                })
        }
    }
}
</script>