<template>
    <div class="inline-grid">
        <div class="grid-actions">
            <v-button @click="create"><fa icon="plus"></fa></v-button>
            <v-button @click="load" :spin="isLoading">refresh</v-button>
        </div>
        <div class="grid-item" v-for="model in models">
            <div class="item-label">
                {{ model[config.labelField] }}
            </div>
            <div class="item-description" v-if="config.descriptionField">
                {{ model[config.descriptionField] }}
            </div>
            <div class="item-actions">
                <a href="#" @click.prevent="edit(model)">
                    <fa icon="pencil-alt"></fa> edit
                </a>
                <a href="#" @click.prevent="remove(model)">
                    <fa icon="trash"></fa> remove
                </a>
            </div>
        </div>
        <v-modal v-if="editingModel" class="inline-grid-editor" width="400px">
            <div class="modal-title">
                <template v-if="isNew">
                    Create {{ config.label }}
                </template>
                <template v-else>
                    Edit {{ config.label }}
                </template>
            </div>
            <v-form @submit="submit" @abort="abort" :buttons="formButtons">
                <slot name="form" :model="editingModel"></slot>
            </v-form>
        </v-modal>
    </div>
</template>

<script>
export default {
    props: {
        config: {
            required: true,
            type: Object,
            default: {
                model: '',
                labelField: '',
                descriptionField: ''
            }
        }
    },
    data: () => ({
        models: [],
        editingModel: null,
        isSaving: false,
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
    computed: {
        $model() {
            return this.$models[this.config.model]
        },
        isNew() {
            return this.editingModel && this.editingModel.id === 0
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
                .then(models => me.models = models)
                .finally(() => {
                    me.isLoading = false
                })
        },
        create() {
            let me = this

            me.editingModel = me.$model.create()
        },
        edit (model) {
            let me = this

            me.editingModel = model
        },
        remove (model) {
            let me = this

            me.$model.remove(model).then(me.load)
        },
        submit({ setLoading, setMessage }) {
            let me = this

            setMessage(null)
            setLoading(true)

            me.isSaving = true
            me.$model.save(me.editingModel)
                .then(() => {
                    me.load()

                    me.editingModel = null
                })
                .catch(error => {
                    setMessage('error', error)
                })
                .finally(() => {
                    setLoading(false)
                })
        },
        abort() {
            let me = this

            me.editingModel = null
        }
    }
}
</script>