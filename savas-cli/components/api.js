const axios = require('axios')
const asnyc = require('async')

module.exports = class API {
    constructor (config) {
        let me = this

        me.config = config
        me.http = axios.create({
            baseURL: me.config.data.remote + '/savas/',
            headers: {
                'X-API-Token': config.data.auth.token
            }
        })
    }

    createRelease ({ version, channel, description }) {
        let me = this

        let data = {
            params: {
                appID: me.config.data.auth.appID,
                version,
                channel,
                description,
                active: 0
            }
        }

        return me.http.post('release/save', data.params)
            .then(response => response.data)
    }

    getReleases () {
        let me = this

        let data = {
            params: {
                applicationID: me.config.data.auth.appID
            }
        }

        return me.http.get('release/list', data)
            .then(response => response.data)
    }

}