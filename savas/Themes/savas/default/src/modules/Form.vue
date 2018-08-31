<template>
    <div class="form-container">
        <v-message v-if="message.type" :type="message.type">
            {{ message.text }}
        </v-message>

        <form @submit.prevent="submit">
            <slot></slot>
        </form>

        <div class="form-buttons" v-if="buttons.length > 0">
            <v-button v-for="(button, key) in buttons"
                      @click="click(button)"
                      :key="key">
                {{ button.label }}
            </v-button>
        </div>

        <div class="loading-container" :class="{ 'is--hidden': !loading }">
            <fa icon="spinner" spin></fa>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        buttons: {
            type: Array,
            default() {
                return []
            }
        }
    },
    data: () => ({
        loading: false,
        message: {
            type: null,
            text: ''
        }
    }),
    methods: {
        submit () {
            let me = this
            let button = me.buttons.find(b => b.primary === true)

            if (button) {
                me.click(button)
            }
        },
        click (button) {
            let me = this
            let actions = {
                setMessage (type, text) {
                    me.message.type = type
                    me.message.text = text
                },
                setLoading (loading) {
                    me.loading = loading
                }
            }

            me.$emit(button.name, actions)
        }
    }
}
</script>