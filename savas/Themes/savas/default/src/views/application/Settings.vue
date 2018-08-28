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
                    <div class="channels-editor">
                        <div class="channel-item">
                            <div class="item-label">
                                stable
                            </div>
                            <div class="item-description">

                            </div>
                        </div>
                        <v-button><fa icon="plus"></fa></v-button>
                    </div>
                </div>
                <div class="form-item">
                    <label for="platforms">
                        Platforms
                    </label>
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