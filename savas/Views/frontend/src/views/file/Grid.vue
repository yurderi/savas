<template>
    <div>
        <v-grid :config="gridConfig" @create="create" @load="load" ref="grid">
            <div class="grid-item file" slot="item" slot-scope="{ model }" @create="create">
                <div class="item-icon">
                    <fa icon="file"></fa>
                </div>
                <div class="item-label">
                    {{ model.displayName }}
                </div>
                <div class="item-meta">
                    <div class="meta-item">
                        {{ platform(model.platformID).label }}
                    </div>
                    <div class="meta-item">
                        {{ bytes(model.size, { unitSeparator: ' ' }) }}
                    </div>
                </div>
                <div class="item-actions">
                    <a href="#" @click.prevent="download(model)">
                        <fa icon="download"></fa>
                    </a>
                    <a href="#" @click.prevent="edit(model)">
                        <fa icon="pencil-alt"></fa>
                    </a>
                    <a href="#" @click.prevent="remove(model)">
                        <fa icon="trash"></fa>
                    </a>
                </div>
            </div>
        </v-grid>

        <v-modal-form :config="formConfig" ref="form" @save="load(true)">
            <template slot="form" slot-scope="{ model }">
                <div class="form-item">
                    <label for="platform">platform</label>
                    <v-select :data="platforms" v-model="model.platformID"></v-select>
                </div>
                <div class="form-item">
                    <label for="file">file</label>
                    <v-file id="file" v-model="model.displayName" ref="file"></v-file>
                </div>
            </template>
        </v-modal-form>
    </div>
</template>

<script>
import bytes from 'bytes'

export default {
    props: {
        application: {
            required: true,
            type: Object,
        },
        release: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            bytes,
            gridConfig: {
                model: this.$models.file,
                columns: 4,
                fetchParams: () => ({
                    releaseID: this.release.id
                })
            },
            formConfig: {
                label: 'file',
                model: this.$models.file,
                override: {
                    submit: this.submit
                }
            },
            platforms: []
        }
    },
    computed: {
        $form() {
            return this.$refs.form
        },
        $grid() {
            return this.$refs.grid
        },
        $platform() {
            return this.$models.platform
        },
        $model() {
            return this.$models.file
        }
    },
    methods: {
        create() {
            let me = this

            me.$form.startEdit()
            me.$form.editingModel.releaseID = me.release.id
        },
        download (model) {
            let me = this
            let url = me.$http.defaults.baseURL + 'frontend/file/download?id=' + model.id + '&filename=' + model.displayName

            window.open(url, '_blank')
        },
        edit(model) {
            let me = this

            me.$form.startEdit(model)
        },
        remove(model) {
            let me = this

            me.$models.file.remove(model)
                .then(() => {
                    me.$grid.load()
                })
        },
        load(refreshGrid) {
            let me = this

            if (refreshGrid) {
                me.$grid.load()
            } else {
                me.$platform.list().then(platforms => me.platforms = platforms)
            }
        },
        submit ({ setLoading, setMessage, setProgress }) {
            let me = this
            let $form = me.$form
            let model = $form.editingModel
            let data = new FormData()
            let config = {
                onUploadProgress: function(progressEvent) {
                    let percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );

                    setProgress(percentCompleted, 'Progressing... ' + percentCompleted + '%')
                }
            }

            for (let key in model) {
                if (model.hasOwnProperty(key)) {
                    data.append(key, model[key])
                }
            }

            data.append('file', me.$refs.file.$refs.fileInput.files[0])

            setMessage(null)
            setProgress(0, 'Progressing...')

            me.$http.post('frontend/file/save', data, config)
                .then(response => response.data)
                .then(response => {
                    if (response.success) {
                        $form.$emit('save')
                        $form.editingModel = null
                    } else {
                        throw response.messages.join('<br />')
                    }
                })
                .catch(error => {
                    if (error instanceof Error) {
                        setMessage('error', error.message)
                    } else {
                        setMessage('error', error)
                    }
                })
                .finally(() => {
                    setLoading(false)
                    setProgress(null)
                })
        },
        platform (id) {
            let me = this

            return me.platforms.find(platform => platform.id === id) || me.$platform.create()
        }
    }
}
</script>