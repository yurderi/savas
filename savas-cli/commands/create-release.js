const Config = require('../components/config')
const API = require('../components/api')

module.exports = ({ _, channel, description }) => {
    let config = new Config()

    if (config.isTouched()) {
        if (config.isAuthenticated()) {
            let api = new API(config)

            api.createRelease({
                version: _[1],
                channel,
                description
            }).then(response => {
                console.log(response)
            })
        } else {
            console.log('you are not authenticated yet')
        }
    } else {
        console.log('savas is not yet initialized')
    }
}