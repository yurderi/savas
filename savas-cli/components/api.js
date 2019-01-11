const axios = require('axios')
const asnyc = require('async')
const path = require('path')
const FormData = require('form-data')
const fs = require('fs-extra')

module.exports = class API {
    constructor (config) {
        let me = this
        
        me.config = config
        me.http = axios.create({
            baseURL: me.config.data.remote + '/',
            headers: {
                'X-API-Token': config.data.auth.token
            }
        })
    }
    
    uploadFile ({filename, version, channel, platform}) {
        let me = this
        
        return new Promise((resolve, reject) => {
            console.log('Finding release...')
            me.getReleaseByVersionAndChannel(version, channel).then(release => {
                if (release) {
                    console.log('Finding platform...')
                    me.getPlatformByName(platform).then(platform => {
                        if (platform) {
                            let data = new FormData()
                            let file = fs.createReadStream(filename)
                            data.append('releaseID', release.id)
                            data.append('platformID', platform.id)
                            data.append('displayName', path.basename(filename))
                            data.append('file', file, {
                                filename: path.basename(filename)
                            })

                            data.append('systemRequirements', '');
                            
                            console.log('Uploading file...')
                            me.http.post('frontend/file/save', data, {
                                headers: data.getHeaders(),
                                maxContentLength: Infinity
                            }).then(result => result.data).then(result => {
                                resolve(result)
                            }).catch(reject)
                        } else {
                            reject('Platform not found')
                        }
                    })
                } else {
                    reject('Release by version and channel not found')
                }
            })
        })
    }
    
    getPlatformByName (name) {
        let me = this
        
        return me.http.get('frontend/platform/list').then(result => result.data).then(({data}) => {
            for (var i = 0, platform = null; i < data.length, platform = data[ i ]; i++) {
                if (platform.label === name) {
                    return platform
                }
            }
            
            return null
        })
    }
    
    getReleaseByVersionAndChannel (version, channel) {
        let me = this
        let data = {
            params: {
                applicationID: me.config.data.auth.appID
            }
        }
        
        return me.http.get('frontend/release/list', data).then(response => response.data).then(({data}) => {
            for (var i = 0, release = null; i < data.length, release = data[ i ]; i++) {
                if (release.version === version && release.channel_label === channel) {
                    return release
                }
            }
            
            return null
        })
    }
    
    createRelease ({version, channel, description, enable}) {
        let me = this
        
        let data = {
            params: {
                appID: me.config.data.auth.appID,
                version,
                channel,
                description,
                active: enable === true ? 1 : 0
            }
        }
        
        return me.http.post('frontend/release/save', data.params).then(response => response.data)
    }
    
    getReleases () {
        let me = this
        
        let data = {
            params: {
                applicationID: me.config.data.auth.appID
            }
        }
        
        return me.http.get('frontend/release/list', data).then(response => response.data)
    }
    
}