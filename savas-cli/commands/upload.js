const Config = require('../components/config')
const API = require('../components/api')
const path = require('path')
const fs = require('fs-extra')

module.exports = (filename, version, {channel, platform}) => {
    let config = new Config()
    
    if (config.isTouched()) {
        if (config.isAuthenticated()) {
            let api = new API(config)
            filename = path.resolve(filename)
            
            fs.exists(filename).then(ok => {
                if (ok) {
                    api.uploadFile({
                        filename,
                        version,
                        channel,
                        platform
                    }).then(result => {
                        if (result.success) {
                            console.log('File were uploaded successfully')
                        } else {
                            console.log('Unable to upload file, see errors below.')
            
                            if ('messages' in result) {
                                result.messages.forEach(m => {
                                    console.log(' - %s', m)
                                })
                            } else {
                                console.log(result)
                            }
                        }
                    }).catch(error => {
                        console.log('Unable to upload file (%s)', error.toString())
                    })
                } else {
                    console.log('File not exists')
                }
            })
        } else {
            console.log('you are not authenticated yet')
        }
    } else {
        console.log('savas is not yet initialized')
    }
}