<template>
    <div>
        <v-grid :config="gridConfig" @create="create" ref="grid">
            <div class="grid-item token" slot="item" slot-scope="{ model }">
                <div class="item-label">
                    {{ model.label }}
                </div>
                <div class="item-token">
                    {{ model.token }}
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
                    <label for="label">Label</label>
                    <v-input type="text" id="label" v-model="model.label"></v-input>
                </div>
                <div class="form-item">
                    <v-checkbox v-model="model.enabled" label="Enabled"></v-checkbox>
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
                model: this.$models.token,
                columns: 1
            },
            formConfig: {
                label: 'api token',
                model: this.$models.token
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
        edit(model) {
            let me = this

            me.$form.startEdit(model)
        },
        remove(model) {
            let me = this

            me.$models.token.remove(model)
                .then(() => {
                    me.load()
                })
        },
        load() {
            let me = this

            me.$grid.load()
        }
    }
}
</script>