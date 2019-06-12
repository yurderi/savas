<template>
    <div class="view is--application-settings">
        <v-header></v-header>
        <v-content>
            <v-breadcrumb :items="breadcrumb"></v-breadcrumb>
            <v-tab-menu>
                <v-tab id="tokens" label="API Tokens">
                    <v-token-grid></v-token-grid>
                </v-tab>
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
import VTokenGrid from '@/views/token/Grid'

export default {
    computed: {
        breadcrumb() {
            return [
                {
                    label: 'Applications',
                    route: { name: 'application-list' }
                },
                {
                    label: 'Settings',
                    route: { name: 'application-settings' }
                }
            ]
        }
    },
    components: {
        VChannelGrid,
        VPlatformGrid,
        VTokenGrid
    }
}
</script>