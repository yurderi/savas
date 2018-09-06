<template>
    <div class="view is--application-settings">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>
            <v-tab-menu>
                <v-tab id="channels" label="Channels">
                    <v-channel-grid></v-channel-grid>
                </v-tab>
                <v-tab id="platforms" label="Platforms">
                    <v-platform-grid></v-platform-grid>
                </v-tab>
            </v-tab-menu>
        </v-content>
    </div>
</template>

<script>
import VChannelGrid from '@/views/channel/Grid'
import VPlatformGrid from '@/views/platform/Grid'

export default {
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
    components: {
        VChannelGrid,
        VPlatformGrid
    }
}
</script>