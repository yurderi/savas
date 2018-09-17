<template>
    <div class="form-modal">
        <v-modal v-if="editingModel" class="form-modal" width="500px">
            <div class="modal-title">
                <template v-if="isNew">
                    Create {{ opts.label }}
                </template>
                <template v-else>
                    Edit {{ opts.label }}
                </template>
            </div>
            <v-form @submit="submit" @abort="abort" :buttons="formButtons">
                <slot name="form" :model="editingModel"></slot>
            </v-form>
        </v-modal>
    </div>
</template>

<script>
import _ from 'lodash'

export default {
    data: () => ({
        opts: {
            label: '',
            override: {}
        },
        editingModel: null,
        isLoading: false,
        formButtons: [
            {
                label: 'Submit',
                name: 'submit',
                primary: true
            },
            {
                label: 'Abort',
                name: 'abort'
            }
        ]
    }),
    props: {
        config: {
            required: true,
            type: Object
        }
    },
    computed: {
        isNew() {
            return this.editingModel && this.editingModel.id === 0
        },
        $model() {
            return this.opts.model
        }
    },
    beforeMount() {
        let me = this

        me.opts = _.extend(me.opts, me.config)
    },
    methods: {
        submit({ setLoading, setMessage, setProgress }) {
            let me = this

            if (typeof me.opts.override.submit === 'function') {
                me.opts.override.submit({ setLoading, setMessage, setProgress })
                return
            }

            setMessage(null)
            setLoading(true)

            me.$model.save(me.editingModel)
                .then(response => {
                    if (response.success) {
                        me.$emit('save')
                        me.editingModel = null
                    } else {
                        setMessage('error', response.messages.join('<br />'))
                    }
                })
                .catch(error => {
                    setMessage('error', error)
                })
                .finally(() => {
                    setLoading(false)
                })
        },
        abort () {
            let me = this

            me.editingModel = null
        },
        startEdit (model) {
            let me = this

            if (model) {
                me.editingModel = model
            } else {
                me.editingModel = me.$model.create()
            }
        }
    }
}
</script>