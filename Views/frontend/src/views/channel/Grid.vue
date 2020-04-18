<template>
    <div>
        <v-grid :config="gridConfig" @create="create" ref="grid">
            <div class="grid-item channel" slot="item" slot-scope="{ model }" @create="create">
                <div class="item-label">
                    {{ model.label }}
                    <small v-if="model.main">
                        (default)
                    </small>
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
        </v-grid>

        <v-modal-form :config="formConfig" ref="form" @save="load">
            <template slot="form" slot-scope="{ model }">
                <div class="form-item">
                    <v-checkbox v-model="model.main" label="Default"></v-checkbox>
                </div>
                <div class="form-item">
                    <label for="label">Label</label>
                    <v-input type="text" id="label" v-model="model.label"></v-input>
                </div>
                <div class="form-item">
                    <label for="short">Shortcut</label>
                    <v-input type="text" id="short" v-model="model.short"></v-input>
                </div>
            </template>
        </v-modal-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            gridConfig: {
                model: this.$models.channel,
                columns: 3
            },
            formConfig: {
                label: 'channel',
                model: this.$models.channel
            }
        }
    },
    computed: {
        $form() {
            return this.$refs.form
        },
        $grid() {
            return this.$refs.grid
        }
    },
    methods: {
        create() {
            let me = this

            me.$form.startEdit()
        },
        edit (model) {
            let me = this

            me.$form.startEdit(model)
        },
        remove (model) {
            let me = this

            me.$models.channel.remove(model)
                .then(() => {
                    me.load()
                })
        },
        load () {
            let me = this

            me.$grid.load()
        },
    }
}
</script>