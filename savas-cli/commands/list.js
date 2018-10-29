const Config = require('../components/config')
const API = require('../components/api')

module.exports = () => {
    let config = new Config()

    if (config.isTouched()) {
        if (config.isAuthenticated()) {
            let api = new API(config)

            api.getReleases()
                .then(releases => {
                    let data = releases.data.map(release => ({
                        id: release.id,
                        channel: release.channel_label,
                        version: release.version,
                        public: release.active ? 'yes' : 'no',
                        description: release.description,
                        created: release.created,
                        changed: release.changed
                    }))

                    console.table('Current releases', data)
                })
        } else {
            console.log('you are not authenticated yet')
        }
    } else {
        console.log('savas is not yet initialized')
    }
}