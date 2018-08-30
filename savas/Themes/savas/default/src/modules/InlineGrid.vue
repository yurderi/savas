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
            <div class="modal-form">
                <slot name="form" :model="editingModel"></slot>
            </div>
            <div class="modal-actions">
                <v-button @click="submit" :spin="isSaving">submit</v-button>
                <v-button @click="abort" :disabled="isSaving">abort</v-button>
            </div>
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
        isLoading: false
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
        submit() {
            let me = this

            me.isSaving = true
            me.$model.save(me.editingModel)
                .then(() => {
                    me.load()
                })
                .catch(error => {
                    console.log(error)
                })
                .finally(() => {
                    me.isSaving = false
                    me.editingModel = null
                })
        },
        abort() {
            let me = this

            me.editingModel = null
        }
    }
}
</script>