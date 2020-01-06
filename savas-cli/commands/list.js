const Config = require('../components/config')
const API = require('../components/api')

require('console.table')

module.exports = () => {
    let config = new Config()

    if (config.isTouched()) {
        if (config.isAuthenticated()) {
            let api = new API(config)

            api.getReleases().then(releases => {
                let data = releases.data.map(release => ({
                    id: release.id,
                    channel: release.channel_label,
                    version: release.version,
                    public: parseInt(release.active) === 1 ? 'yes' : 'no',
                    files: release.files,
                    // description: release.description,
                    created: release.created,
                    changed: release.changed
                }))
            
                if (data.length > 0) {
                    console.table('Available releases', data)
                } else {
                    console.log('No releases yet')
                }
            })
        } else {
            console.log('you are not authenticated yet')
        }
    } else {
        console.log('savas is not yet initialized')
    }
}