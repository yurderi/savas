const axios = require('axios')
const fs = require('fs')
const _ = require('lodash')

module.exports = class UpdateService {

    constructor (settings) {
        let me = this

        me.defaultConfig = {
            host: '',
            ssl: false,

            id: null,
            channel: null,
            platform: null,
            version: null
        }

        me.opts = _.extend(me.defaultConfig, settings)
        me.http = axios.create({
            baseURL: (me.opts.ssl ? 'https://' : 'http://') + me.opts.host
        })
    }

    getUpdate () {
        let me = this
        let params = {
            id: me.opts.id,
            channel: me.opts.channel,
            platform: me.opts.platform,
            version: me.opts.version
        }

        return new Promise ((resolve, reject) => {
            me.http.get('api/v1/updates', { params })
                .then(response => response.data)
                .then(response => {
                    if (response.isNewer) {
                        delete response.isNewer

                        resolve(response)
                    } else {
                        reject()
                    }
                })
                .catch(error => {
                    console.log(error)
                    reject(error)
                })
        })
    }

    download (update, settings) {
        let me = this

        axios.get(update.filename, { responseType: 'stream' })
            .then(response => {
                let total = response.headers['content-length']
                let received = 0
                let percentage = 0

                response.data.on('data', (chunk) => {
                    if (total > 0) {
                        received += chunk.length

                        let _percentage = Math.round(received / total * 100)

                        if (_percentage !== percentage) {
                            percentage = _percentage

                            settings.progress({
                                total,
                                current: received,
                                percentage
                            })
                        }
                    }
                })

                response.data.on('end', () => {
                    settings.resolve({
                        filename: settings.destination
                    })
                })

                response.data.pipe(fs.createWriteStream(settings.destination))
            })
            .catch(settings.reject || function() {})
    }

}