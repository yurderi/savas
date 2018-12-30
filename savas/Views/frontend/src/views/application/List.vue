<template>
    <div class="view is--application-list">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>

            <v-grid ref="grid" :config="gridConfig" @create="create">
                <div class="grid-item application" slot="item" slot-scope="{ model }">
                    <div class="item-label">
                        {{ model.label }}
                    </div>
                    <div class="item-description">
                        {{ model.description }}
                    </div>
                    <div class="item-information">
                        <div class="information-item">
                            <div class="item-label">version</div>
                            <div class="item-value">{{ model.currentVersion }}</div>
                        </div>
                        <div class="information-item">
                            <div class="item-label">releases</div>
                            <div class="item-value">{{ model.releaseCount }}</div>
                        </div>
                        <div class="information-item">
                            <div class="item-label">downloads</div>
                            <div class="item-value">{{ model.downloadCount }}</div>
                        </div>
                        <div class="information-item">
                            <div class="item-label">feedback</div>
                            <div class="item-value">{{ model.feedbackCount }}</div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <a href="#" @click.prevent="edit(model)">
                            <fa icon="pencil-alt"></fa>
                            edit
                        </a>
                        <a href="#" @click.prevent="remove(model)">
                            <fa icon="trash"></fa>
                            remove
                        </a>
                    </div>
                </div>
            </v-grid>
        </v-content>
    </div>
</template>

<script>
export default {
    data() {
        return {
            gridConfig: {
                model: this.$models.application,
                columns: 2,
            }
        }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: 'applications',
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
        remove(model) {
            let me = this

            me.$model.remove(model)
                .then(success => {
                    me.$grid.load()
                })
        }
    }
}
</script>