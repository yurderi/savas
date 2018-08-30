<template>
    <div class="view is--application-settings">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>
            <v-form @submit.prevent="submit">
                <div class="form-item">
                    <label for="channels">
                        Channels
                    </label>
                    <v-inline-grid :config="gridConfig.channels">
                        <template slot="form" slot-scope="{ model }">
                            <div class="form-item">
                                <label for="label">Label</label>
                                <v-input type="text" id="label" v-model="model.label"></v-input>
                            </div>
                            <div class="form-item">
                                <label for="description">Description</label>
                                <v-input type="textarea" id="description" v-model="model.description"></v-input>
                            </div>
                        </template>
                    </v-inline-grid>
                </div>
                <div class="form-item">
                    <label for="platforms">
                        Platforms
                    </label>
                    <v-inline-grid :config="gridConfig.platforms">
                        <template slot="form" slot-scope="{ model }">
                            <div class="form-item">
                                <label for="label">Label</label>
                                <v-input type="text" id="label" v-model="model.label"></v-input>
                            </div>
                            <div class="form-item">
                                <label for="description">Description</label>
                                <v-input type="textarea" id="description" v-model="model.description"></v-input>
                            </div>
                        </template>
                    </v-inline-grid>
                </div>
                <div class="form-buttons">
                    <v-button>Submit</v-button>
                </div>
            </v-form>
        </v-content>
    </div>
</template>

<script>
export default {
    data: () => ({
        gridConfig: {
            channels: {
                model: 'channel',
                label: 'Channel',
                labelField: 'label',
                descriptionField: 'description'
            },
            platforms: {
                model: 'platform',
                label: 'Platform',
                labelField: 'label',
                descriptionField: 'description'
            }
        }
    }),
    computed: {
        breadcrumb() {
            return [
                {
                    label: 'applications',
                    route: { name: 'application-list' }
                },
                {
                    label: 'settings',
                    route: { name: 'application-settings' }
                }
            ]
        }
    },
    methods: {
        submit() {
            let me = this


        }
    }
}
</script>