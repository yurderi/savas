<template>
    <div class="grid-header">
        <div class="search">
            <v-input type="text" placeholder="Filter ..." v-model="searchInput" @keydown.esc="searchInput=''"></v-input>
            <span class="search-result" v-if="searchInput.length > 0">
                {{ resultCount > 0 ? (resultCount + ' results') : 'nothing found' }}
            </span>
        </div>

        <div class="grid-controls" v-if="buttons.length > 0">
            <v-button v-for="(button, key) in buttons"
                      @click="click(button)"
                      :key="key"
                      :spin="button.spin">
                <fa v-if="button.icon" :icon="button.icon"></fa>
                {{ button.label }}
            </v-button>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        searchInput: this.search || ''
    }),
    props: {
        search: {
            type: String,
            default: ''
        },
        resultCount: {
            type: Number,
            default: 0
        },
        buttons: {
            type: Array,
            default() {
                return []
            }
        }
    },
    watch: {
        searchInput(value) {
            let me = this

            me.$emit('input', value)
        }
    },
    methods: {
        click (button) {
            let me = this

            me.$emit('click', button.action)
        }
    }
}
</script>