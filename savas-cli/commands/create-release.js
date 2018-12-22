const Config = require('../components/config')
const API = require('../components/api')

module.exports = (version, {channel, description, enable}) => {
    let config = new Config()
    
    if (config.isTouched()) {
        if (config.isAuthenticated()) {
            let api = new API(config)
    
            api.createRelease({
                version,
                channel,
                description,
                enable
            }).then(response => {
                if (response.success) {
                    console.log('the release were created successfully')
                } else {
                    console.log('the release could not be created. see errors below')
                    
                    if ('messages' in response) {
                        response.messages.forEach(m => {
                            console.log(' - %s', m)
                        })
                    } else {
                        console.log(response)
                    }
                }
            })
        } else {
            console.log('you are not authenticated yet')
        }
    } else {
        console.log('savas is not yet initialized')
    }
}