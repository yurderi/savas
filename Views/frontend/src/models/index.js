import Vue from 'vue'

const files = require.context('.', false, /\.js$/)
const models = {}

files.keys().forEach(key => {
    if (key === './index.js') {
        return
    }

    let name = key.replace(/(\.\/|\.js)/g, '')
    let definition = files(key).default

    let model = {
        ...definition,
        create () {
            let me = this
            let data = {}

            for (let i = 0, field = null; i < me.fields.length, field = me.fields[i]; i++) {
                let value = null

                switch (field.type) {
                    case 'integer':
                        value = field.default !== undefined ? field.default : 0
                    break;
                    case 'string':
                        value = field.default !== undefined ? field.default : '';
                    break;
                    case 'boolean':
                        value = field.default !== undefined ? field.default : false
                    break;
                }

                data[field.name] = value
            }

            return data
        },
        save (data) {
            let me = this

            return Vue.http.post(me.proxy.save, data)
                .then(response => response.data)
        },
        get (id) {
            let me = this

            return Vue.http.post(me.proxy.detail, { id })
                .then(response => response.data)
                .then(response => {
                    return me.__normalizeRow(response.data)
                })
        },
        remove (model) {
            let me = this

            return Vue.http.post(me.proxy.remove, { id: model.id })
                .then(response => response.data)
                .then(response => {
                    return response.success
                })
        },
        list (params) {
            let me = this

            return Vue.http.post(me.proxy.list, params)
                .then(response => response.data)
                .then(response => {
                    let rows = response.data

                    rows.map(row => me.__normalizeRow(row))

                    return rows
                })
        },
        filter (models, search) {
            let me = this
            let fields = me.fields.filter(field => field.filterable === true)

            return models.filter(model => {
                for (let i = 0, field; i < fields.length, field = fields[i]; i++) {
                    if (model[field.name].indexOf(search) > -1) {
                        return true
                    }
                }

                return false
            })
        },
        __normalizeRow (data) {
            let me = this

            for (let i = 0, field = null; i < me.fields.length, field = me.fields[i]; i++) {
                let value = data[field.name]

                switch (field.type) {
                    case 'integer':
                        value = parseInt(value)
                        break;
                    case 'string':
                        value = String(value)
                        break;
                    case 'boolean':
                        value = Boolean(parseInt(value))
                }

                data[field.name] = value
            }

            return data
        }
    }

    models[name] = model
})

export default models
